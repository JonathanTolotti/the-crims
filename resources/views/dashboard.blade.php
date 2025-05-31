<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-wrap sm:flex-nowrap justify-between items-start gap-4">
                    {{-- Mensagem de Boas-Vindas (Esquerda) --}}
                    <div class="flex-grow">
                        <h3 class="text-2xl font-semibold">Bem-vindo de volta, {{ Auth::user()->name }}!</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Pronto para continuar sua jornada no submundo?</p>
                    </div>

                    {{-- Card de Status VIP (Direita) --}}
                    <div class="ml-0 sm:ml-4 mt-4 sm:mt-0 flex-shrink-0 w-full sm:w-auto">
                        <div class="bg-yellow-50 dark:bg-gray-700 p-4 rounded-lg border border-yellow-300 dark:border-yellow-600 shadow-sm">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-yellow-400 dark:bg-yellow-500 text-white mr-3">
                                    {{-- Ícone de VIP (Estrela) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.324l5.397.782c.596.086.836.835.402 1.226l-3.878 4.148a.563.563 0 00-.162.522l.899 5.698a.562.562 0 01-.818.592l-4.762-2.734a.563.563 0 00-.526 0l-4.762 2.734a.562.562 0 01-.818-.592l.899-5.698a.563.563 0 00-.162.522L2.044 11.03a.562.562 0 01.402-1.226l5.397-.782a.563.563 0 00.475-.324L11.48 3.5z" />
                                    </svg>
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-yellow-700 dark:text-yellow-300 uppercase tracking-wider">Status VIP</p>
                                    @if(Auth::user()->is_vip && Auth::user()->vip_expires_at > now())
                                        <p class="font-semibold text-yellow-800 dark:text-yellow-100">Ativo até: <span class="font-normal">{{ \Carbon\Carbon::parse(Auth::user()->vip_expires_at)->format('d/m/Y') }}</span></p>
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
                                    {{-- Substituir por dados reais do jogador --}}
                                    <span id="player-energy">100</span> / <span id="player-max-energy">100</span>
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
                                    {{-- Substituir por dados reais do jogador --}}
                                    $<span id="player-money">1,250</span>
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
                                    {{-- Substituir por dados reais do jogador --}}
                                    $<span id="player-money">1,250</span>
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
                                    {{-- Substituir por dados reais do jogador --}}
                                    <span id="player-level">5</span>
                                </p>
                            </div>
                        </div>
                        {{-- Barra de XP --}}
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 60%" id="player-xp-bar"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">
                                <span id="player-current-xp">150</span> / <span id="player-next-level-xp">250</span> XP
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seção de Ações Rápidas ou Links do Jogo --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações do Jogo</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="" class="block p-6 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg shadow-md transition ease-in-out duration-150">
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
