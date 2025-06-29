<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\VipTier;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Order $order): Session
    {
        $order->loadMissing('storeProduct');

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $order->storeProduct->stripe_price_id,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('game.shop.success'),
            'cancel_url' => route('game.shop.cancel'),
            'metadata' => [
                'order_uuid' => $order->uuid,
            ]
        ]);
    }

}
