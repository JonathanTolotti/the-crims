<?php
namespace App\Jobs;

use App\Models\User;
use App\Services\CharacterAttributeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RegenerateEnergyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Cria uma nova instância do job.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Executa o job.
     */
    public function handle(CharacterAttributeService $characterAttributeService): void
    {
        $energyToRegenerate = 10;

        $effectiveMaxEnergy = $characterAttributeService->getMaxEnergy($this->user);

        if ($this->user->energy_points < $effectiveMaxEnergy) {
            $characterAttributeService->addEnergy($this->user, $energyToRegenerate);

            Log::channel('energy_regeneration')->info("Energia regenerada para o usuário ID: {$this->user->id}. Nova energia: {$this->user->fresh()->energy_points}");
        }
    }
}
