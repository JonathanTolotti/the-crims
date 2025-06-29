<?php

namespace App\Http\Controllers\Game;

use App\Enums\StoreProductTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\StoreProduct;
use App\Models\VipTier;
use App\Services\OrderService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ShopController extends Controller
{
    public function __construct(protected StripeService $stripeService, protected OrderService $orderService)
    {
    }

    public function index(): View
    {
        $products = StoreProduct::query()->where('is_active', true)->orderBy('price_in_cents')->get();

        $groupedProducts = $products->groupBy(fn($product) => $product->product_type->value);

        $user = auth()->user();
        $currentUser = [
            'vip_tier_id' => $user->vip_tier_id,
            'cash_balance' => $user->cash_balance,
        ];

        return view('game.shop.index', [
            'groupedProducts' => $groupedProducts,
            'currentUser' => $currentUser,
            'productTypeEnum' => [
                'vip' => StoreProductTypeEnum::VIP_SUBSCRIPTION->value,
                'cash' => StoreProductTypeEnum::CASH_PACKAGE->value,
            ]
        ]);
    }

    public function checkout(Request $request, StoreProduct $storeProduct)
    {
        // Passo 1: Delega a criação do pedido para o OrderService
        $order = $this->orderService->createPendingOrder($request->user(), $storeProduct);

        // Passo 2: Delega a criação da sessão de pagamento para o StripeService
        $session = $this->stripeService->createCheckoutSession($order);

        // Passo 3: Atualiza nosso pedido com o ID da sessão do Stripe
        $order->update(['stripe_session_id' => $session->id]);

        // Passo 4: Redireciona o jogador para o pagamento
        return redirect($session->url);
    }

    public function success()
    {
        return "Pagamento realizado com sucesso! Seu produto será entregue em breve.";
    }

    public function cancel()
    {
        return "A compra foi cancelada.";
    }
}
