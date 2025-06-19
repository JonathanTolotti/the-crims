<?php

namespace Database\Seeders;

use App\Enums\ItemTypeEnum;
use App\Models\Item;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(InventoryService $inventoryService): void
    {
        $user = User::query()->first();

        if (!$user) {
            $this->command->error('Nenhum usuário encontrado. Rode o seeder de usuários primeiro ou registre um.');
            return;
        }

        DB::table('user_items')->where('user_id', $user->id)->delete();

        $allItems = Item::all();

        if ($allItems->isEmpty()) {
            $this->command->warn('Nenhum item encontrado na tabela de itens. Rode o ItemSeeder primeiro.');
            return;
        }

        foreach ($allItems as $item) {
            $quantity = match ($item->item_type) {
                ItemTypeEnum::EQUIPMENT => 1,
                ItemTypeEnum::CONSUMABLE, ItemTypeEnum::REFINING_MATERIAL => 1000,
                default => 1,
            };

            $inventoryService->addItem($user, $item, $quantity);
            $this->command->info("{$quantity}x {$item->name} adicionado(s) ao inventário.");
        }
    }
}
