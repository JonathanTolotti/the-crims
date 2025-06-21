<?php
namespace App\Jobs;

use App\Models\User;
use App\Services\CharacterAttributeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        if ($this->user->energy_points < $this->user->max_energy_points) {
            $characterAttributeService->addEnergy($this->user, $energyToRegenerate);
        }
    }
}
