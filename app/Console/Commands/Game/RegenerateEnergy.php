<?php
namespace App\Console\Commands\Game;

use App\Jobs\RegenerateEnergyJob;
use App\Models\User;
use App\Services\CharacterAttributeService;
use Illuminate\Console\Command;

class RegenerateEnergy extends Command
{
    protected $signature = 'game:regenerate-energy';

    protected $description = 'Dispara jobs para regenerar a energia de todos os jogadores ativos.';

    public function handle(CharacterAttributeService $characterAttributeService): void
    {
        $this->info('Buscando jogadores para regenerar energia...');

        User::query()
            ->whereColumn('energy_points', '<', 'max_energy_points')
            ->orWhereNotNull('vip_tier_id')
            ->chunkById(100, function ($users) use ($characterAttributeService) {

                $dispatchedCount = 0;

                foreach ($users as $user) {
                    $effectiveMaxEnergy = $characterAttributeService->getMaxEnergy($user);
                    if ($user->energy_points < $effectiveMaxEnergy) {
                        RegenerateEnergyJob::dispatch($user);
                        $dispatchedCount++;
                    }
                }

                if ($dispatchedCount > 0) {
                    $this->comment($dispatchedCount . ' jobs de regeneração de energia foram despachados para este bloco.');
                }
            });

        $this->info('Processo de despacho de jobs finalizado.');
    }
}
