<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg flex flex-col relative
            @if($product->product_type === \App\Enums\StoreProductTypeEnum::VIP_SUBSCRIPTION) border-2 border-yellow-500 @else border-2 border-transparent @endif">

    <div class="p-6 text-gray-900 dark:text-gray-100 flex-grow">
        <h3 class="text-2xl font-bold @if($product->product_type === \App\Enums\StoreProductTypeEnum::VIP_SUBSCRIPTION) text-yellow-500 @else text-green-500 @endif">
            {{ $product->name }}
        </h3>
        <p class="mt-2 text-gray-600 dark:text-gray-400 min-h-[40px]">{{ $product->description }}</p>
    </div>

    <div class="p-6 bg-gray-50 dark:bg-gray-700/50 mt-auto">
        <div class="text-3xl font-extrabold text-gray-900 dark:text-white">
            R$ {{ number_format($product->price_in_cents / 100, 2, ',', '.') }}
        </div>

        @php
            $isCurrentUserVipTier = $product->product_type === \App\Enums\StoreProductTypeEnum::VIP_SUBSCRIPTION &&
                                    $currentUser['vip_tier_id'] === ($product->metadata['vip_tier_id'] ?? null);
        @endphp

        @if ($isCurrentUserVipTier)
            <div class="w-full text-center mt-4 px-6 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-200 dark:text-green-200 dark:bg-green-800/50">
                Plano VIP Ativo
            </div>
        @else
            <form method="POST" action="{{ route('game.shop.checkout', $product->uuid) }}" class="mt-4">
                @csrf
                <x-primary-button class="w-full justify-center text-lg">
                    Comprar
                </x-primary-button>
            </form>
        @endif
    </div>
</div>
