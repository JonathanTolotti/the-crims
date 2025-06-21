<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VipTier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vip = VipTier::query()->where('name', '=', 'VIP Premium')->first();
        User::factory()->create([
            'name' => 'Joni',
            'email' => 'joni@joni.com',
            'password' => Hash::make('12345678'),
            'vip_tier_id' => $vip->id,
            'vip_expires_at' => now()->addDays($vip->duration_in_days),
            'character_class_id' => 1,
            'crims_coin' => 10000,
            'money' => 10000000,
            'is_admin' => true,
        ]);
    }
}
