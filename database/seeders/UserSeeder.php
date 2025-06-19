<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->create([
            'name' => 'Joni',
            'email' => 'joni@joni.com',
            'password' => Hash::make('12345678'),
            'is_vip' => true,
            'vip_expires_at' => now()->addDays(365),
            'character_class_id' => 1,
            'crims_coin' => 10000,
            'money' => 10000000,
            'is_admin' => true,
        ]);
    }
}
