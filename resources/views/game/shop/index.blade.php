<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Loja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            {{-- Seção de Assinaturas VIP --}}
            @if(isset($groupedProducts[$productTypeEnum['vip']]))
                <section>
                    <div class="mb-6">
                        {{-- Banner da Categoria --}}
                        <!--<img src="{{ asset('images/shop/vip_banner.png') }}" alt="Assinaturas VIP" class="w-full h-48 object-cover rounded-lg shadow-lg"> -->
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white mt-4">Assinaturas VIP</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400">Torne-se um membro VIP para acelerar seu progresso com benefícios exclusivos.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($groupedProducts[$productTypeEnum['vip']] as $product)
                            @include('game.shop._product_card', ['product' => $product, 'currentUser' => $currentUser])
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Seção de Pacotes de CrimsCoin --}}
            @if(isset($groupedProducts[$productTypeEnum['cash']]))
                <section>
                    <div class="mb-6">
                        {{-- Banner da Categoria --}}
{{--                        <img src="{{ asset('images/shop/crimscoin_banner.png') }}" alt="Pacotes de CrimsCoin" class="w-full h-48 object-cover rounded-lg shadow-lg">--}}
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white mt-4">Pacotes de CrimsCoin</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400">Adquira CrimsCoin, a moeda premium do jogo, para obter vantagens e itens especiais.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($groupedProducts[$productTypeEnum['cash']] as $product)
                            @include('game.shop._product_card', ['product' => $product, 'currentUser' => $currentUser])
                        @endforeach
                    </div>
                </section>
            @endif

        </div>
    </div>
</x-app-layout>
