<?php

namespace Database\Seeders;

use App\Models\Crime;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CrimeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('crime_item')->truncate();

        $crime1 = Crime::query()->where('name', 'Roubar uma Carteira')->first();
        $crime3 = Crime::query()->where('name', 'Clonar Cartões de Crédito')->first();
        $item_shard = Item::query()->where('name', 'Fragmento de Aprimoramento')->first();
        $item_dagger = Item::query()->where('name', 'Adaga Gasta')->first();

        if ($crime1 && $item_shard) {
            // "Roubar Carteira" tem 2.5% de chance de dropar um Fragmento
            $crime1->possibleLoot()->attach($item_shard->id, ['drop_chance' => 0.025]);
        }

        if ($crime1 && $item_dagger) {
            // "Roubar uma Carteira" tem 30% de chance de dropar uma Adaga Gasta
            $crime1->possibleLoot()->attach($item_dagger->id, ['drop_chance' => 0.3]);
        }
    }
}
