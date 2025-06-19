<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inventário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Aumentamos o número de colunas para os itens ficarem menores --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse ($items as $userItem)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col border dark:border-gray-700">
                        {{-- Container da Imagem com posicionamento relativo para os badges --}}
                        <div class="p-2 bg-gray-100 dark:bg-gray-900 flex justify-center items-center h-32 relative">
                            @if($userItem->item->image_path)
                                <img src="{{ asset($userItem->item->image_path) }}" alt="{{ $userItem->item->name }}" class="max-h-full max-w-full object-contain">
                            @else
                                <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">Sem Imagem</span>
                                </div>
                            @endif

                            {{-- Badge de Quantidade (para itens empilháveis) --}}
                            @if($userItem->item->stackable)
                                <div class="absolute top-2 right-2 bg-slate-700 bg-opacity-80 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    x{{ $userItem->quantity }}
                                </div>
                            @endif
                        </div>

                        {{-- Detalhes do Item --}}
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-md text-gray-900 dark:text-gray-100 truncate" title="{{ $userItem->item->name }}">{{ $userItem->item->name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $userItem->item->description }}</p>
                            </div>

                            {{-- Seção de Bônus (apenas para equipamentos) --}}
                            @if($userItem->item->item_type === \App\Enums\ItemTypeEnum::EQUIPMENT)
                                <div class="mt-2 space-y-1 text-xs">
                                    @if($userItem->item->strength_bonus > 0)
                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                            <span class="font-semibold">Força:</span>
                                            <span class="ml-1">+{{ $userItem->item->strength_bonus }}</span>
                                        </div>
                                    @endif
                                    @if($userItem->item->dexterity_bonus > 0)
                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                            <span class="font-semibold">Destreza:</span>
                                            <span class="ml-1">+{{ $userItem->item->dexterity_bonus }}</span>
                                        </div>
                                    @endif
                                    @if($userItem->item->intelligence_bonus > 0)
                                        <div class="flex items-center text-gray-600 dark:text-gray-300">
                                            <span class="font-semibold">Inteligência:</span>
                                            <span class="ml-1">+{{ $userItem->item->intelligence_bonus }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Botões de Ação e Badge de Tier --}}
                            <div class="mt-4 pt-4 border-t dark:border-gray-700 flex justify-between items-center">
                                @if($userItem->item->item_type === \App\Enums\ItemTypeEnum::EQUIPMENT)
                                    <div class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">
                                        T{{ $userItem->tier }}
                                    </div>
                                    @if($userItem->is_equipped)
                                        <form method="POST" action="{{ route('game.inventory.unequip', $userItem) }}">
                                            @csrf
                                            <x-secondary-button type="submit" class="text-xs">Desequipar</x-secondary-button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('game.inventory.equip', $userItem) }}">
                                            @csrf
                                            <x-secondary-button type="submit" class="text-xs">Equipar</x-secondary-button>
                                        </form>
                                    @endif
                                @elseif($userItem->item->item_type === \App\Enums\ItemTypeEnum::CONSUMABLE)
                                    <span></span> {{-- Espaço para alinhar --}}
                                    <form method="POST" action="{{ route('game.inventory.use', $userItem) }}">
                                        @csrf
                                        <x-secondary-button type="submit" class="text-xs">Usar</x-secondary-button>
                                    </form>

                                @elseif($userItem->item->item_type === \App\Enums\ItemTypeEnum::REFINING_MATERIAL)
                                    <span></span> {{-- Espaço para alinhar --}}
                                    <a href="{{ route('game.refinery.index') }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                        Refinar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-900 dark:text-gray-100">
                            Seu inventário está vazio.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
