<?php

namespace App\Services;

use App\Models\User;
use App\Models\LevelDefinition;

class LevelProgressionService
{
    public function addExperience(User $user, int $experienceGained): User
    {
        if ($experienceGained <= 0) {
            return $user;
        }

        $user->experience_points += $experienceGained;

        $this->checkForLevelUp($user);

        $user->save();
        return $user;
    }

    protected function checkForLevelUp(User $user): void
    {
        $user->loadMissing('levelDefinition');
        $currentLevelDefinition = $user->levelDefinition;

        if (!$currentLevelDefinition || $currentLevelDefinition->experience_to_next_level <= 0) {
            return;
        }

        while ($user->experience_points >= $currentLevelDefinition->experience_to_next_level) {
            $experienceForThisLevel = $currentLevelDefinition->experience_to_next_level;

            $nextLevelId = $currentLevelDefinition->id + 1;
            $nextLevelDefinition = LevelDefinition::query()->find($nextLevelId);

            if ($nextLevelDefinition) {
                $user->experience_points -= $experienceForThisLevel;
                $user->current_level_id = $nextLevelId;

                /** Adicionar no futuro, quando tiver o evento de subir de nível.
                $user->energy_points = 100;
                $user->max_energy_points = 100;
                $user->money += 1000;
                $user->base_strength += 5;
                $user->base_dexterity += 5;
                $user->base_intelligence += 5;
                 */

                // event(new PlayerLeveledUpEvent($user, $currentLevelDefinition, $nextLevelDefinition)); // Fase 5


                $user->load('levelDefinition');
                $currentLevelDefinition = $user->levelDefinition;

                if ($currentLevelDefinition->experience_to_next_level <= 0) break;

            } else {
                $user->experience_points = $experienceForThisLevel;
                break;
            }
        }
    }

    public function getExperienceProgress(User $user): array
    {
        $user->loadMissing('levelDefinition');
        $currentLevelDefinition = $user->levelDefinition;

        if (!$currentLevelDefinition) {
            return [
                'current_xp_in_level' => 0,
                'xp_for_next_level' => 1,
                'percentage' => 0,
                'current_level_name' => 'N/A',
                'current_level_number' => $user->current_level_id,
            ];
        }

        $xpForNextLevel = $currentLevelDefinition->experience_to_next_level;
        $currentXpInLevel = $user->experience_points;

        if ($xpForNextLevel <= 0) {
            $percentage = 100;
        } else {
            $percentage = ($currentXpInLevel / $xpForNextLevel) * 100;
        }

        return [
            'current_xp_in_level' => $currentXpInLevel,
            'xp_for_next_level' => $xpForNextLevel,
            'percentage' => round(min($percentage, 100)),
            'current_level_name' => $currentLevelDefinition->name,
            'current_level_number' => $currentLevelDefinition->id,
        ];
    }
}
