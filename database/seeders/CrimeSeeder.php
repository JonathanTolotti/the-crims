<?php

namespace Database\Seeders;

use App\Enums\CharacterAttributeEnum;
use App\Models\Crime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Crime::query()->delete();

        Crime::query()->create([
            'name' => 'Roubar uma Carteira',
            'description' => 'Um pequeno furto para começar. Rápido, mas com pouca recompensa.',
            'energy_cost' => 10,
            'money_reward_min' => 20,
            'money_reward_max' => 50,
            'experience_reward' => 5,
            'cooldown_seconds' => 30,
            'required_level_id' => 1,
            'primary_attribute' => CharacterAttributeEnum::DEXTERITY,
            'base_success_chance' => 70,
        ]);

        Crime::query()->create([
            'name' => 'Extorquir um Vendedor Ambulante',
            'description' => 'Use sua força para convencer um vendedor a te dar uma parte dos lucros do dia.',
            'energy_cost' => 20,
            'money_reward_min' => 80,
            'money_reward_max' => 150,
            'experience_reward' => 15,
            'cooldown_seconds' => 30,
            'required_level_id' => 2,
            'primary_attribute' => CharacterAttributeEnum::STRENGTH,
            'base_success_chance' => 50,
        ]);

        Crime::query()->create([
            'name' => 'Clonar Cartões de Crédito',
            'description' => 'Uma operação mais sofisticada que exige inteligência para copiar dados e sacar dinheiro.',
            'energy_cost' => 30,
            'money_reward_min' => 200,
            'money_reward_max' => 400,
            'experience_reward' => 30,
            'cooldown_seconds' => 45,
            'required_level_id' => 3,
            'primary_attribute' => CharacterAttributeEnum::INTELLIGENCE,
            'base_success_chance' => 40,
        ]);
    }
}
