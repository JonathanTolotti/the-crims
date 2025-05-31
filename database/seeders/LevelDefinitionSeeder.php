<?php

namespace Database\Seeders;

use App\Models\LevelDefinition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LevelDefinition::query()->create([
            'level_number' => 1,
            'experience_to_next_level' => 100,
        ]);

        LevelDefinition::query()->create([
            'level_number' => 2,
            'experience_to_next_level' => 250,
        ]);

        LevelDefinition::query()->create([
            'level_number' => 3,
            'experience_to_next_level' => 500,
        ]);

        LevelDefinition::query()->create([
            'level_number' => 4,
            'experience_to_next_level' => 750,
        ]);

        LevelDefinition::query()->create([
            'level_number' => 5,
            'experience_to_next_level' => 1000,
        ]);
    }
}
