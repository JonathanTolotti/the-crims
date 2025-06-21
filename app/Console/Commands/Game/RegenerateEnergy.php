<?php
namespace App\Console\Commands\Game;

use App\Jobs\RegenerateEnergyJob;
use App\Models\User;
use Illuminate\Console\Command;

class RegenerateEnergy extends Command
{
    protected $signature = 'game:regenerate-energy';

    protected $description = 'Dispara jobs para regenerar a energia de todos os jogadores ativos.';

    public function handle(): void
    {
        $this->info('Buscando jogadores para regenerar energia...');

        User::whereColumn('energy_points', '<', 'max_energy_points')
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    RegenerateEnergyJob::dispatch($user);
                }
                $this->comment(count($users) . ' jobs de regeneração de energia foram despachados.');
            });

        $this->info('Processo de despacho de jobs finalizado.');
    }
}
