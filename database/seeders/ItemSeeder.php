<?php

namespace Database\Seeders;

use App\Enums\EquipmentSlotEnum;
use App\Enums\ItemTypeEnum;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::query()->delete();

        // Exemplo de EQUIPAMENTO
        Item::query()->create([
            'name' => 'Adaga Gasta',
            'description' => 'Uma adaga simples e gasta, melhor que nada.',
            'item_type' => ItemTypeEnum::EQUIPMENT,
            'image_path' => 'images/items/worn_dagger.png',
            'stackable' => false,
            'equipment_slot' => EquipmentSlotEnum::WEAPON,
            'dexterity_bonus' => 2,
        ]);

        // Exemplo de CONSUMÍVEL
        Item::query()->create([
            'name' => 'Poção de Energia Pequena',
            'description' => 'Restaura 25 pontos de energia instantaneamente.',
            'item_type' => ItemTypeEnum::CONSUMABLE,
            'image_path' => 'images/items/small_energy_potion.png',
            'stackable' => true,
            'effect_type' => 'RESTORE_ENERGY',
            'effect_amount' => 25,
        ]);

        // Exemplo de MATERIAL DE REFINAMENTO
        Item::query()->create([
            'name' => 'Fragmento de Aprimoramento',
            'description' => 'Um fragmento usado para tentar melhorar equipamentos.',
            'item_type' => ItemTypeEnum::REFINING_MATERIAL,
            'image_path' => 'images/items/upgrade_shard.png',
            'stackable' => true,
        ]);
    }
}
