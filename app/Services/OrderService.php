<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StoreProduct;
use App\Models\User;

class OrderService
{
    /**
     * Cria um novo pedido com o status 'pending' para um usuário e produto.
     */
    public function createPendingOrder(User $user, StoreProduct $storeProduct): Order
    {
        return Order::query()->create([
            'user_id' => $user->id,
            'store_product_id' => $storeProduct->id,
            'status' => 'pending',
            'total_in_cents' => $storeProduct->price_in_cents,
        ]);
    }
}
