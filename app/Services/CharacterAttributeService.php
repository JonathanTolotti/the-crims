<?php

namespace App\Services;

use App\Models\User;

class CharacterAttributeService
{
    public function getEffectiveAttribute(User $user, string $attributeName): int
    {
        $user->loadMissing('characterClass');

        $baseAttributeField = 'base_' . $attributeName;
        $baseAttributeValue = $user->{$baseAttributeField} ?? 0;

        $classMultiplier = 0;
        if ($user->characterClass) {
            $modifierField = $attributeName . '_modifier';
            $classMultiplier = $user->characterClass->{$modifierField} ?? 0;
        }

        $itemMultiplier = 0;
        $itemFlatBonus = 0;

        $effectiveValue = $baseAttributeValue * (1 + $classMultiplier + $itemMultiplier) + $itemFlatBonus;

        return (int) round($effectiveValue);
    }

    public function getCurrentEnergy(User $user): int
    {
        return $user->energy_points;
    }

    public function getMaxEnergy(User $user): int
    {
        return $user->max_energy_points;
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
