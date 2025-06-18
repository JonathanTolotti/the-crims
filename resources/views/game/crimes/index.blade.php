<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cometer Crimes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alertas de Sucesso e Erro --}}
            @if(session('success'))
                <div
                    class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div
                    class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-200 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse ($crimes as $crime)
                    @php
                        // Estas variáveis PHP são usadas para a verificação inicial do popover
                        $levelOk = $user->current_level_id >= $crime->required_level_id;
                        $energyOk = $user->energy_points >= $crime->energy_cost;
                    @endphp
                    {{-- Inicializamos o componente Alpine para cada card --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                         x-data="crimeCard(
                {{ $levelOk ? 'true' : 'false' }},
                {{ $energyOk ? 'true' : 'false' }},
                '{{ $crime->cooldown_ends_at }}'
             )">
                        <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col md:flex-row gap-6">
                            {{-- Coluna de Informações (não muda) --}}
                            <div class="flex-grow">
                                <h3 class="text-xl font-bold">{{ $crime->name }}</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $crime->description }}</p>
                                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm border-t dark:border-gray-700 pt-4">
                                    <div><span class="font-semibold text-gray-500 dark:text-gray-400">Energia:</span><br>-{{ $crime->energy_cost }}</div>
                                    <div><span class="font-semibold text-gray-500 dark:text-gray-400">Nível Req:</span><br>{{ $crime->required_level_id }}</div>
                                    <div><span class="font-semibold text-gray-500 dark:text-gray-400">Recompensa XP:</span><br>~{{ $crime->experience_reward ?? 0 }}</div> {{-- Adicionei ?? 0 para segurança --}}
                                    <div><span class="font-semibold text-gray-500 dark:text-gray-400">Atributo:</span><br><span class="capitalize">{{ $crime->primary_attribute }}</span></div>
                                </div>
                            </div>
                            {{-- Coluna de Ação --}}
                            <div class="flex-shrink-0 w-full md:w-auto md:border-l md:dark:border-gray-700 md:pl-6 text-center md:text-left">
                                <p class="text-gray-600 dark:text-gray-400 font-medium">Chance de Sucesso:</p>
                                <p class="text-3xl font-bold my-1">{{ $crime->success_chance }}%</p>
                                <form method="POST" action="{{ route('game.crimes.attempt', $crime->uuid) }}" class="flex items-center gap-2 mt-2">
                                    @csrf
                                    <x-primary-button class="flex-grow justify-center" ::disabled="!canAttempt">
                                        {{-- O texto do botão agora é controlado pelo Alpine --}}
                                        <span x-text="buttonText"></span>
                                    </x-primary-button>

                                    {{-- Ícone de Bloqueio com Popover --}}
                                    <div x-show="!canAttempt" x-data="{ open: false }" class="relative">
                                        <div @mouseenter="open = true" @mouseleave="open = false">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400 dark:text-gray-500">
                                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div x-show="open" x-transition class="absolute z-10 bottom-full mb-2 -translate-x-1/2 left-1/2 w-80 p-2 text-sm text-left text-white bg-gray-900 dark:bg-black rounded-lg shadow-lg">
                                            <h4 class="font-bold border-b border-gray-700 pb-1 mb-1">Motivo do Bloqueio</h4>
                                            <ul class="list-disc list-inside">
                                                @if(!$levelOk) <li>Nível insuficiente</li> @endif
                                                @if(!$energyOk) <li>Energia insuficiente</li> @endif
                                                <li x-show="isOnCooldown">Em tempo de espera</li>
                                            </ul>
                                            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900 dark:bg-black"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            Nenhum crime disponível para o seu nível no momento.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function crimeCard(levelOk, energyOk, cooldownEndsAtIso) {
            return {
                levelOk: levelOk,
                energyOk: energyOk,
                isOnCooldown: false,
                buttonText: 'Tentar',
                canAttempt: false,
                init() {
                    // Define o estado inicial do botão e do cooldown
                    if (cooldownEndsAtIso) {
                        const cooldownEnd = new Date(cooldownEndsAtIso).getTime();
                        if (cooldownEnd > new Date().getTime()) {
                            this.isOnCooldown = true;
                            this.startCountdown(cooldownEnd);
                        }
                    }
                    this.updateButtonState();
                },
                startCountdown(cooldownEnd) {
                    const countdown = setInterval(() => {
                        const now = new Date().getTime();
                        const distance = cooldownEnd - now;

                        // Se o tempo acabou, limpa o intervalo e atualiza o estado
                        if (distance < 0) {
                            clearInterval(countdown);
                            this.isOnCooldown = false;
                            this.buttonText = 'Tentar';
                            this.updateButtonState();
                            return;
                        }

                        // Calcula minutos e segundos
                        const minutes = Math.floor(distance / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        // Atualiza o texto do botão com o formato MM:SS
                        this.buttonText = this.buttonText = 'Aguarde (' + minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0') + ')';
                        this.updateButtonState(); // Garante que o botão permaneça desabilitado
                    }, 1000);
                },
                updateButtonState() {
                    // O botão só pode ser clicado se todas as condições forem verdadeiras
                    this.canAttempt = this.levelOk && this.energyOk && !this.isOnCooldown;
                }
            }
        }
    </script>
</x-app-layout>
