<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Refinaria de Equipamentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Alertas --}}
            @if(session('success'))
                <div class="mb-4 p-4 text-sm bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 rounded-lg shadow" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 text-sm bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-200 rounded-lg shadow" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Seção de Materiais --}}
                    <div class="border-b dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-medium">Seus Materiais de Refinamento</h3>
                        <div class="mt-2 space-y-1">
                            @forelse ($ownedRefiningMaterials as $material)
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-bold text-indigo-400">{{ $material->quantity }}x</span> {{ $material->item->name }}
                                </p>
                            @empty
                                <p class="text-sm text-gray-500">Você não possui materiais de refinamento.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Lista de Equipamentos --}}
                    <h3 class="text-lg font-medium mb-4">Seus Equipamentos</h3>
                    <div class="space-y-4">
                        @forelse ($equipmentData as $data)
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg gap-4">
                                {{-- Info do Item --}}
                                <div class="flex items-center">
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-800 rounded-md flex items-center justify-center mr-4 flex-shrink-0">
                                        @if($data->userItem->item->image_path)
                                            <img src="{{ asset('storage/'.$data->userItem->item->image_path) }}" alt="{{ $data->userItem->item->name }}" class="max-h-full max-w-full object-contain">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold">{{ $data->userItem->item->name }} <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">T{{ $data->userItem->tier }}</span></p>
                                        @if($data->rule)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Chance para T{{$data->rule->to_tier}}:
                                                <span class="font-semibold">{{ $data->rule->success_chance }}%</span>
                                            </p>
                                            {{-- Consequência da Falha --}}
                                            <p class="text-xs mt-1
                                                @switch($data->rule->failure_outcome)
                                                    @case(\App\Enums\RefineFailureOutcomeEnum::DOWNGRADE_TIER) text-yellow-600 dark:text-yellow-500 @break
                                                    @case(\App\Enums\RefineFailureOutcomeEnum::DESTROY_ITEM) text-red-600 dark:text-red-500 @break
                                                    @default text-gray-500 dark:text-gray-400
                                                @endswitch
                                            ">
                                                @switch($data->rule->failure_outcome)
                                                    @case(\App\Enums\RefineFailureOutcomeEnum::DOWNGRADE_TIER) Risco de diminuir o Tier @break
                                                    @case(\App\Enums\RefineFailureOutcomeEnum::DESTROY_ITEM) RISCO DE QUEBRAR O ITEM @break
                                                    @default Mantém o Tier @break
                                                @endswitch
                                            </p>
                                        @else
                                            <p class="text-sm font-semibold text-green-500">Tier Máximo!</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Requisitos e Botão --}}
                                <div class="w-full sm:w-auto sm:text-right">
                                    @if($data->rule)
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                            <p class="font-semibold">Custo do Aprimoramento:</p>
                                            <span>{{ $data->rule->required_item_quantity }}x {{ $data->rule->requiredItem->name }}</span>
                                            <span class="mx-1">|</span>
                                            <span>${{ number_format($data->rule->required_money) }}</span>
                                        </div>
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Ícone de Bloqueio com Popover --}}
                                            @if(!$data->canAttempt)
                                                <div x-data="{ open: false }" class="relative">
                                                    <div @mouseenter="open = true" @mouseleave="open = false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400 dark:text-gray-500">
                                                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div x-show="open" x-transition class="absolute z-10 bottom-full mb-2 -right-2 w-48 p-2 text-sm text-left text-white bg-gray-900 dark:bg-black rounded-lg shadow-lg">
                                                        <h4 class="font-bold border-b border-gray-700 pb-1 mb-1">Requisitos não atendidos:</h4>
                                                        <ul class="list-disc list-inside">
                                                            @foreach($data->reasonsToBlock as $reason)
                                                                <li>{{ $reason }}</li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="absolute bottom-0 right-3 -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900 dark:bg-black"></div>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- Botão de Ação --}}
                                            <form method="POST" action="{{ route('game.refinery.refine', $data->userItem) }}">
                                                @csrf
                                                <x-primary-button :disabled="!$data->canAttempt">
                                                    Refinar para T{{$data->rule->to_tier}}
                                                </x-primary-button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p>Você não possui equipamentos para refinar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
