<?php

namespace App\Services;

use App\DataTransferObjects\CrimeOutcomeDTO;
use App\Models\Crime;
use App\Models\CrimeLog;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CrimeService
{
    private const ATTRIBUTE_SUCCESS_FACTOR = 1.5;
    private const MIN_SUCCESS_CHANCE = 5;
    private const MAX_SUCCESS_CHANCE = 95;

    public function __construct(
        protected CharacterAttributeService $characterAttributeService,
        protected LevelProgressionService   $levelProgressionService,
        protected InventoryService $inventoryService
    )
    {
    }

    public function getCrimeDataForUser(User $user): Collection
    {
        $availableCrimes = Crime::query()->where('required_level_id', '<=', $user->current_level_id + 2)
            ->orderBy('required_level_id')
            ->get();

        $lastAttempts = CrimeLog::query()->where('user_id', $user->id)
            ->whereIn('crime_id', $availableCrimes->pluck('id'))
            ->select('crime_id', DB::raw('MAX(attempted_at) as last_attempted_at'))
            ->groupBy('crime_id')
            ->get()
            ->keyBy('crime_id');

        return $availableCrimes->map(function ($crime) use ($user, $lastAttempts) {
            $lastAttempt = $lastAttempts->get($crime->id);
            $cooldownEndsAt = $lastAttempt ? Carbon::parse($lastAttempt->last_attempted_at)->addSeconds($crime->cooldown_seconds) : null;
            $isOnCooldown = $cooldownEndsAt && $cooldownEndsAt->isFuture();

            return (object) [
                'uuid' => $crime->uuid,
                'name' => $crime->name,
                'description' => $crime->description,
                'energy_cost' => $crime->energy_cost,
                'required_level_id' => $crime->required_level_id,
                'primary_attribute' => $crime->primary_attribute->value,
                'success_chance' => $this->getSuccessChance($user, $crime),
                'experience_reward' => $crime->experience_reward,
                'is_on_cooldown' => $isOnCooldown,
                'cooldown_ends_at' => $isOnCooldown ? $cooldownEndsAt->toIso8601String() : null,
            ];
        });
    }

    /**
     * Calcula a chance do crime ocorrer, para ser exibido na view
     *
     * @param User $user
     * @param Crime $crime
     * @return int
     */
    public function getSuccessChance(User $user, Crime $crime): int
    {
        $playerAttributeValue = $this->characterAttributeService->getEffectiveAttribute($user, $crime->primary_attribute->value);

        $successChance = $crime->base_success_chance + (($playerAttributeValue - $crime->required_level_id) * self::ATTRIBUTE_SUCCESS_FACTOR);

        return (int) max(self::MIN_SUCCESS_CHANCE, min($successChance, self::MAX_SUCCESS_CHANCE));
    }

    /**
     * Gerencia os métodos para cometer um crime
     *
     * @param User $user
     * @param Crime $crime
     * @return CrimeOutcomeDTO
     */
    public function attemptCrime(User $user, Crime $crime): CrimeOutcomeDTO
    {
        if ($preconditionFailure = $this->checkPreconditions($user, $crime)) {
            return $preconditionFailure;
        }

        $isSuccessful = $this->calculateSuccess($user, $crime);

        return $this->processOutcome($user, $crime, $isSuccessful);
    }

    private function checkPreconditions(User $user, Crime $crime): ?CrimeOutcomeDTO
    {
        if ($user->current_level_id < $crime->required_level_id) {
            return new CrimeOutcomeDTO(false, 'Você não tem o nível necessário para este crime.');
        }

        if ($this->characterAttributeService->getCurrentEnergy($user) < $crime->energy_cost) {
            return new CrimeOutcomeDTO(false, 'Energia insuficiente para tentar este crime.');
        }

        $cooldownInSeconds = $crime->cooldown_seconds;

        if ($user->is_vip) {
            $user->loadMissing('vipTier');
            $cooldownMultiplier = $user->vipTier?->cooldown_reduction_multiplier ?? 1.0;
            $cooldownInSeconds *= $cooldownMultiplier;
        }

        $lastAttempt = CrimeLog::where('user_id', $user->id)
            ->where('crime_id', $crime->id)
            ->latest('attempted_at')
            ->first();

        if ($lastAttempt) {
            $cooldownEndsAt = Carbon::parse($lastAttempt->attempted_at)->addSeconds($cooldownInSeconds);
            if (Carbon::now()->lt($cooldownEndsAt)) {
                $timeLeft = $cooldownEndsAt->diffForHumans(['parts' => 2, 'short' => true]);
                return new CrimeOutcomeDTO(false, "Aguarde. Você poderá tentar este crime novamente $timeLeft.");
            }
        }
        return null;
    }

    private function calculateSuccess(User $user, Crime $crime): bool
    {
        $playerAttributeValue = $this->characterAttributeService->getEffectiveAttribute($user, $crime->primary_attribute->value);

        $successChance = $crime->base_success_chance + (($playerAttributeValue - $crime->required_level_id) * self::ATTRIBUTE_SUCCESS_FACTOR);

        $finalSuccessChance = (int)max(self::MIN_SUCCESS_CHANCE, min($successChance, self::MAX_SUCCESS_CHANCE));

        return rand(1, 100) <= $finalSuccessChance;
    }

    private function processOutcome(User $user, Crime $crime, bool $isSuccessful): CrimeOutcomeDTO
    {
        return DB::transaction(function () use ($user, $crime, $isSuccessful) {
            $this->characterAttributeService->spendEnergy($user, $crime->energy_cost);

            return $isSuccessful ? $this->handleSuccess($user, $crime) : $this->handleFailure($user, $crime);
        });
    }

    private function handleSuccess(User $user, Crime $crime): CrimeOutcomeDTO
    {
        $moneyGained = rand($crime->money_reward_min, $crime->money_reward_max);
        $experienceGained = $crime->experience_reward;

        if ($user->is_vip) {
            $user->loadMissing('vipTier');
            $rewardMultiplier = $user->vipTier?->reward_multiplier ?? 1.0;
            $moneyGained *= $rewardMultiplier;
            $experienceGained *= $rewardMultiplier;
        }

        $finalMoneyGained = (int) round($moneyGained);
        $finalExperienceGained = (int) round($experienceGained);

        $user->money += $finalMoneyGained;
        $this->levelProgressionService->addExperience($user, $finalExperienceGained);
        $droppedItem = $this->handleItemDrop($user, $crime);
        $user->save();

        $this->logAttempt($user, $crime, true, $moneyGained, $experienceGained);

        $message = "Sucesso! Você ganhou R$ $finalMoneyGained e $finalExperienceGained XP.";

        if ($droppedItem) {
            $message .= " Você encontrou: {$droppedItem->name}!";
        }

        return new CrimeOutcomeDTO(true, $message, $moneyGained, $experienceGained);
    }
    private function handleFailure(User $user, Crime $crime): CrimeOutcomeDTO
    {
        $this->logAttempt($user, $crime, false);

        $message = "Falha! Você não obteve sucesso e perdeu {$crime->energy_cost} de energia.";
        return new CrimeOutcomeDTO(false, $message);
    }

    private function logAttempt(User $user, Crime $crime, bool $wasSuccessful, int $moneyGained = 0, int $experienceGained = 0): void
    {
        CrimeLog::query()->create([
            'user_id' => $user->id,
            'crime_id' => $crime->id,
            'was_successful' => $wasSuccessful,
            'money_gained' => $moneyGained,
            'experience_gained' => $experienceGained,
            'attempted_at' => Carbon::now(),
        ]);
    }

    /**
     * Cuida da chance de dropar o item
     *
     * @param User $user
     * @param Crime $crime
     * @return Item|null
     */
    private function handleItemDrop(User $user, Crime $crime): ?Item
    {
        $possibleLoot = $crime->possibleLoot;

        if ($possibleLoot->isEmpty()) return null;

        $user->loadMissing('vipTier');
        $dropRateMultiplier = $user->is_vip ? ($user->vipTier?->drop_rate_multiplier ?? 1.0) : 1.0;

        foreach ($possibleLoot as $lootableItem) {
            $chance = $lootableItem->pivot->drop_chance * $dropRateMultiplier;

            if ((rand(1, 1000) / 1000) <= $chance) {
                $this->inventoryService->addItem($user, $lootableItem);
                return $lootableItem;
            }
        }

        return null;
    }

}
