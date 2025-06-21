<?php

namespace Database\Seeders;

use App\Models\VipTier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VipTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VipTier::query()->delete();

        // Pacote VIP Básico
        VipTier::query()->create([
            'name' => 'VIP Básico',
            'description' => 'Um pacote inicial para dar um impulso na sua jornada.',
            'price_in_cents' => 499, // Ex: R$ 4,99
            'duration_in_days' => 30,
            'reward_multiplier' => 1.15, // +15%
            'drop_rate_multiplier' => 1.25, // +25%
            'cooldown_reduction_multiplier' => 0.90, // -10%
            'max_energy_bonus' => 25,
        ]);

        // Pacote VIP Premium
        VipTier::query()->create([
            'name' => 'VIP Premium',
            'description' => 'A experiência VIP completa com os melhores benefícios.',
            'price_in_cents' => 999, // Ex: R$ 9,99
            'duration_in_days' => 30,
            'reward_multiplier' => 1.30, // +30%
            'drop_rate_multiplier' => 1.75, // +75%
            'cooldown_reduction_multiplier' => 0.70, // -30%
            'max_energy_bonus' => 75,
        ]);

        $this->command->info('Tiers de VIP criados com sucesso!');
    }
}
