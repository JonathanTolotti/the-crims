<?php

namespace App\Services;

use App\Enums\ItemTypeEnum;
use App\Models\Item;
use App\Models\User;
use App\Models\UserItem;

class InventoryService
{
    public function __construct(
        protected CharacterAttributeService $characterAttributeService,
        protected LevelProgressionService $levelProgressionService
    ) {
    }

    public function addItem(User $user, Item $item, int $quantity = 1): UserItem
    {
        if ($item->stackable) {
            $userItem = $user->inventory()
                ->where('item_id', $item->id)
                ->where('is_equipped', false)
                ->first();

            if ($userItem) {
                $userItem->increment('quantity', $quantity);
                return $userItem->fresh();
            }
        }

        return UserItem::query()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Usa um item, aplicando as regras conforme o tipo de item
     *
     * @param User $user
     * @param UserItem $userItem
     * @return array
     */
    public function useItem(User $user, UserItem $userItem): array
    {
        $item = $userItem->item;

        if ($item->item_type !== ItemTypeEnum::CONSUMABLE) {
            return ['success' => false, 'message' => 'Este item não pode ser consumido.'];
        }

        match ($item->effect_type) {
            'RESTORE_ENERGY' =>$this->characterAttributeService->addEnergy($user, $item->effect_amount),
            'ADD_XP' => $this->levelProgressionService->addExperience($user, $item->effect_amount),
            default => ['success' => false, 'message' => 'Tipo de efeito desconhecido.']
        };

        if ($item->stackable && $userItem->quantity > 1) {
            $userItem->decrement('quantity');
        } else {
            $userItem->delete();
        }

        return ['success' => true, 'message' => "Você usou: {$item->name}."];
    }
}
