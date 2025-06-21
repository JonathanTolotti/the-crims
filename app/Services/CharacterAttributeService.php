<?php

namespace App\Services;

use App\Models\User;

class CharacterAttributeService
{
    private const TIER_BONUS_MULTIPLIER = 0.15;

    public function getEffectiveAttribute(User $user, string $attributeName): int
    {
        $user->loadMissing('characterClass', 'equippedItems.item');

        $baseAttributeField = 'base_' . $attributeName;
        $baseAttributeValue = $user->{$baseAttributeField} ?? 0;

        $classMultiplier = 0;
        if ($user->characterClass) {
            $modifierField = $attributeName . '_modifier';
            $classMultiplier = $user->characterClass->{$modifierField} ?? 0;
        }

        $bonusFromItems = 0;

        foreach ($user->equippedItems as $equippedUserItem) {
            $bonusField = $attributeName . '_bonus';
            $itemBaseBonus = $equippedUserItem->item->{$bonusField} ?? 0;

            if ($itemBaseBonus > 0) {
                $tierMultiplier = 1 + ($equippedUserItem->tier * self::TIER_BONUS_MULTIPLIER);

                $bonusFromItems += $itemBaseBonus * $tierMultiplier;
            }
        }

        $effectiveValue = ($baseAttributeValue * (1 + $classMultiplier)) + $bonusFromItems;

        return (int) round($effectiveValue);
    }

    public function getCurrentEnergy(User $user): int
    {
        return $user->energy_points;
    }

    public function getMaxEnergy(User $user): int
    {
        $user->loadMissing('vipTier');

        $baseMaxEnergy = $user->max_energy_points;
        $vipBonus = 0;

        if ($user->is_vip) {
            $vipBonus = $user->vipTier?->max_energy_bonus ?? 0;
        }

        return $baseMaxEnergy + $vipBonus;
    }

    public function spendEnergy(User $user, int $amount): bool
    {
        if ($user->energy_points >= $amount) {
            $user->energy_points -= $amount;
            $user->save();
            return true;
        }
        return false;
    }

    public function addEnergy(User $user, int $amount): void
    {
        $maxEnergy = $this->getMaxEnergy($user);
        $user->energy_points = min($maxEnergy, $user->energy_points + $amount);
        $user->save();
    }
}
