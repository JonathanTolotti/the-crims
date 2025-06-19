<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LevelDefinitionSeeder::class,
            CharacterClassSeeder::class,
            CrimeSeeder::class,
            UserSeeder::class,
            ItemSeeder::class,
            CrimeItemSeeder::class,
            UserInventorySeeder::class,
            TierUpgradeRuleSeeder::class,
        ]);
    }
}
