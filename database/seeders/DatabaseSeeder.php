<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            VipTierSeeder::class,
            LevelDefinitionSeeder::class,
            CharacterClassSeeder::class,
            CrimeSeeder::class,
            UserSeeder::class,
            ItemSeeder::class,
            CrimeItemSeeder::class,
            UserInventorySeeder::class,
            TierUpgradeRuleSeeder::class,
            StoreProductSeeder::class,
        ]);
    }
}
