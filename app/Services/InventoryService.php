<?php

namespace App\Services;

use App\Models\Item;
use App\Models\User;
use App\Models\UserItem;

class InventoryService
{
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
}
