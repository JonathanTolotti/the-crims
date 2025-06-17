<?php

namespace App\Http\Controllers\Game;

use App\Services\CharacterAttributeService;
use App\Services\LevelProgressionService;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    protected LevelProgressionService $levelProgressionService;
    protected CharacterAttributeService $characterAttributeService;

    public function __construct(
        LevelProgressionService $levelProgressionService,
        CharacterAttributeService $characterAttributeService
    ) {
        $this->levelProgressionService = $levelProgressionService;
        $this->characterAttributeService = $characterAttributeService;
    }

    public function index()
    {
        $user = Auth::user()->load('levelDefinition', 'characterClass');

        $experienceProgress = $this->levelProgressionService->getExperienceProgress($user);

        $effectiveStrength = $this->characterAttributeService->getEffectiveAttribute($user, 'strength');
        $effectiveDexterity = $this->characterAttributeService->getEffectiveAttribute($user, 'dexterity');
        $effectiveIntelligence = $this->characterAttributeService->getEffectiveAttribute($user, 'intelligence');
        $maxEnergy = $this->characterAttributeService->getMaxEnergy($user);

        return view('game.dashboard.index', [
            'user' => $user,
            'experienceProgress' => $experienceProgress,
            'effectiveStrength' => $effectiveStrength,
            'effectiveDexterity' => $effectiveDexterity,
            'effectiveIntelligence' => $effectiveIntelligence,
            'maxEnergy' => $maxEnergy,
        ]);
    }

}
