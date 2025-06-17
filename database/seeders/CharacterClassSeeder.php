<?php

namespace Database\Seeders;

use App\Models\CharacterClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CharacterClass::query()->create([
            'id' => 1,
            'name' => 'Operative',
            'description' => 'Um agente versátil, equilibrado em todas as áreas do submundo, ideal para iniciantes.',
            'strength_modifier' => 0.10,
            'dexterity_modifier' => 0.20,
            'intelligence_modifier' => 0.15,
        ]);
    }
}
