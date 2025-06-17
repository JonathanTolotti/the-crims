<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card de Boas-Vindas com Atributos e Status VIP Integrado --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-wrap sm:flex-nowrap justify-between items-start gap-x-6 gap-y-4">
                    {{-- Coluna da Esquerda: Mensagem de Boas-Vindas e Atributos --}}
                    <div class="flex-grow space-y-4">
                        {{-- Mensagem de Boas-Vindas --}}
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Bem-vindo de volta, {{ $user->name }}!</h3>
                            @if ($user->characterClass)
                                <p class="mt-1 text-lg font-medium text-indigo-400 dark:text-indigo-500">{{ $user->characterClass->name }}</p>
                            @endif
                            <p class="mt-1 text-gray-600 dark:text-gray-400">Sua jornada no submundo continua.</p>
                        </div>

                        {{-- Seção de Atributos --}}
                        <div class="pt-2">
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Seus Atributos:</h4>
                            <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm">
                                <div class="flex items-center">
                                    {{-- Ícone de Força (Exemplo) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5 text-gray-400 dark:text-gray-500">
                                        <path fill-rule="evenodd" d="M11.25 2.25c.414 0 .75.336.75.75v.038a12.448 12.448 0 015.097 2.086c.381.28.466.804.186 1.185l-.001.002c-.269.361-.767.456-1.142.22a9.447 9.447 0 00-4.14-1.196V16.5a.75.75 0 01-1.5 0V5.302a9.447 9.447 0 00-4.14 1.196c-.375.236-.873.141-1.142-.22a.75.75 0 01.186-1.185A12.448 12.448 0 018.25 3.038V2.986a.75.75 0 01.75-.75h1.5c.414 0 .75.336.75.75V3a11.7 11.7 0 012.918 1.423c.21.127.312.374.285.617a.41.41 0 01-.412.362A10.09 10.09 0 0012 4.546a10.09 10.09 0 00-1.04.856.408.408 0 01-.412-.362.407.407 0 01.285-.617A11.7 11.7 0 0112 3v-.014c0-.414.336-.75.75-.75h1.5zM4.25 9.75A.75.75 0 015 9h1.958c.217 0 .424.056.612.162l1.825 1.044a.75.75 0 010 1.288l-1.825 1.044a1.236 1.236 0 00-.612.162H5a.75.75 0 01-.75-.75V9.75zM15.75 9.75A.75.75 0 0015 9h-1.958a1.236 1.236 0 00-.612.162l-1.825 1.044a.75.75 0 000 1.288l1.825 1.044c.188.106.395.162.612.162H15a.75.75 0 00.75-.75V9.75z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Força:</span>
                                    <span class="ml-1 font-semibold text-gray-800 dark:text-gray-200">{{ $effectiveStrength }}</span>
                                </div>
                                <div class="flex items-center">
                                    {{-- Ícone de Destreza (Exemplo) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5 text-gray-400 dark:text-gray-500">
                                        <path fill-rule="evenodd" d="M10 2.5c-.276 0-.5.224-.5.5v1.5c0 .197.116.372.289.452L6.25 7.25a.5.5 0 00-.25.434V10a.75.75 0 00.75.75h1.375c.338 0 .652-.135.88-.371l1.246-1.318a.75.75 0 011.002-.066l1.875 1.25a.75.75 0 00.938-.132l2.032-2.54A.75.75 0 0016.25 7V3a.75.75 0 00-.75-.75H10.5a.5.5 0 00-.5.5zm3.25 9.75c0-.276-.224-.5-.5-.5H8.75a.5.5 0 00-.5.5v1.5c0 .197.116.372.289.452L12.08 16.5a.5.5 0 00.49-.034l3.18-2.226A.75.75 0 0016.25 13V12.25zM7.72 11.548L6.375 10.75H5a.75.75 0 00-.75.75v1.75a.75.75 0 00.75.75h2.317a.5.5 0 00.403-.192L9.266 12.25l-1.548-.702z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Destreza:</span>
                                    <span class="ml-1 font-semibold text-gray-800 dark:text-gray-200">{{ $effectiveDexterity }}</span>
                                </div>
                                <div class="flex items-center">
                                    {{-- Ícone de Inteligência (Exemplo) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5 text-gray-400 dark:text-gray-500">
                                        <path fill-rule="evenodd" d="M10 2.5c-.276 0-.5.224-.5.5v1.5a.5.5 0 001 0V3c0-.276-.224-.5-.5-.5zM6.75 3.25A.75.75 0 006 4v1.5a.75.75 0 001.5 0V4a.75.75 0 00-.75-.75zm6.5 0A.75.75 0 0012.5 4v1.5a.75.75 0 001.5 0V4a.75.75 0 00-.75-.75zM4 8a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 8zm0 4a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75a.75.75 0 01-.75-.75zm0 4a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Inteligência:</span>
                                    <span class="ml-1 font-semibold text-gray-800 dark:text-gray-200">{{ $effectiveIntelligence }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Coluna da Direita: Status VIP --}}
                    <div class="ml-0 sm:ml-auto mt-4 sm:mt-0 flex-shrink-0 w-full sm:max-w-xs md:max-w-sm"> {{-- Ajustado para sm:ml-auto e max-width --}}
                        <div class="bg-yellow-50 dark:bg-gray-700 p-4 rounded-lg border border-yellow-300 dark:border-yellow-600 shadow-sm h-full flex flex-col justify-center">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-yellow-400 dark:bg-yellow-500 text-white mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.324l5.397.782c.596.086.836.835.402 1.226l-3.878 4.148a.563.563 0 00-.162.522l.899 5.698a.562.562 0 01-.818.592l-4.762-2.734a.563.563 0 00-.526 0l-4.762 2.734a.562.562 0 01-.818-.592l.899-5.698a.563.563 0 00-.162.522L2.044 11.03a.562.562 0 01.402-1.226l5.397-.782a.563.563 0 00.475-.324L11.48 3.5z" />
                                    </svg>
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-yellow-700 dark:text-yellow-300 uppercase tracking-wider">Status VIP</p>
                                    @if($user->is_vip && $user->vip_expires_at && \Carbon\Carbon::parse($user->vip_expires_at)->isFuture())
                                        <p class="font-semibold text-yellow-800 dark:text-yellow-100">Ativo até: <span class="font-normal">{{ \Carbon\Carbon::parse($user->vip_expires_at)->format('d/m/Y') }}</span></p>
                                    @else
                                        <p class="font-semibold text-gray-700 dark:text-gray-300">Inativo</p>
                                        <a href="#" class="text-xs text-yellow-600 dark:text-yellow-400 hover:underline">Torne-se VIP!</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- O restante do seu dashboard (grid de Energia, Dinheiro, Nível, etc., e Ações do Jogo) continua abaixo --}}

            {{-- Seção de Status Rápido do Personagem --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                {{-- Card de Energia --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-500 bg-opacity-75 text-white mr-4">
                                {{-- Ícone de Energia (Exemplo) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Energia</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $user->energy_points }} / {{ $maxEnergy }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card de Dinheiro --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-75 text-white mr-4">
                                {{-- Ícone de Dinheiro (Exemplo) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 11.219 12.768 11 12 11c-.768 0-1.536.219-2.121.727l-.879.659M7.5 14.25l-2.489-1.87M16.5 14.25l2.489-1.87" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dinheiro</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($user->money, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-75 text-white mr-4">
                                {{-- Ícone de Dinheiro (Exemplo) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 7.5A5.25 5.25 0 1015.5 16.5M8.25 10.5h6m-6 3h6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CrimsCoin</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($user->crims_coin, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card de Nível --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75 text-white mr-4">
                                {{-- Ícone de Nível/XP (Exemplo) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nível</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $experienceProgress['current_level_number'] }}
                                </p>
                            </div>
                        </div>
                        {{-- Barra de XP --}}
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $experienceProgress['percentage'] }}%"></div>
                            </div>
                            @if($experienceProgress['xp_for_next_level'] > 0)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">
                                    {{ number_format($experienceProgress['current_xp_in_level'], 0, ',', '.') }} / {{ number_format($experienceProgress['xp_for_next_level'], 0, ',', '.') }} XP
                                </p>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">Nível Máximo</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seção de Ações Rápidas ou Links do Jogo --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações do Jogo</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="{{route('game.crimes.index')}}" class="block p-6 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg shadow-md transition ease-in-out duration-150">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Cometer Crimes</h4>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Busque sua fortuna e reputação.</p>
                        </a>
                        <a href="" class="block p-6 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg shadow-md transition ease-in-out duration-150">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Inventário</h4>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie seus itens e equipamentos.</p>
                        </a>
                        {{-- Adicione mais links/ações conforme o jogo evolui --}}
                        <a href="#" class="block p-6 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg shadow-md transition ease-in-out duration-150">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Loja (Em Breve)</h4>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Compre e venda itens valiosos.</p>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
