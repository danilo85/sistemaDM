<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        {{-- CABEÇALHO COM NAVEGAÇÃO E RESUMO --}}
        <div class="mb-4">
            <div class="flex justify-between items-center">
                {{-- Botão Mês Anterior --}}
                <button wire:click="mesAnterior" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>

                <div class="text-center">
                    <h3 class="text-xl font-semibold capitalize text-gray-900 dark:text-gray-100">
                        {{ $data->translatedFormat('F Y') }}
                    </h3>
                    <button wire:click="mesAtual" class="text-xs text-blue-500 hover:underline">
                        Voltar para o mês atual
                    </button>
                </div>

                {{-- Botão Próximo Mês --}}
                <button wire:click="proximoMes" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            {{-- Resumo Financeiro do Mês (dentro do widget) --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="bg-green-50 dark:bg-green-900/50 p-2 rounded-lg">
                    <span class="text-xs text-green-800 dark:text-green-300 block">Receitas</span>
                    <span class="text-lg font-bold text-green-600 dark:text-green-400">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</span>
                </div>
                <div class="bg-red-50 dark:bg-red-900/50 p-2 rounded-lg">
                    <span class="text-xs text-red-800 dark:text-red-300 block">Despesas</span>
                    <span class="text-lg font-bold text-red-600 dark:text-red-400">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</span>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/50 p-2 rounded-lg">
                    <span class="text-xs text-blue-800 dark:text-blue-300 block">Saldo</span>
                    <span class="text-lg font-bold {{ $saldoDoMes >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-500' }}">
                        R$ {{ number_format($saldoDoMes, 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- GRADE DO CALENDÁRIO --}}
        @php
            $firstDayOfMonth = $data->copy()->startOfMonth();
            $daysInMonth = $data->daysInMonth;
            $startBlankDays = $firstDayOfMonth->dayOfWeek; 
        @endphp
        <div class="grid grid-cols-7 gap-2 text-center text-sm mt-4">
            @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $day)
                <div class="font-semibold text-gray-500 dark:text-gray-400 text-xs">{{ $day }}</div>
            @endforeach

            @for ($i = 0; $i < $startBlankDays; $i++) <div></div> @endfor

            @for ($day = 1; $day <= $daysInMonth; $day++)
            <a href="{{ route('transacoes.index', ['ano' => $data->year, 'mes' => $data->month, 'dia' => $day]) }}"
            class="relative block text-center">
                <div class="h-10 w-10 mx-auto flex items-center justify-center rounded-full transition-colors duration-200
                            {{ now()->format('Y-m-d') == $data->copy()->setDay($day)->format('Y-m-d') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-gray-700' }}">
                    {{ $day }}
                </div>
                {{-- Bolinhas de transação --}}
                @if ($diasComTransacoes->has($day))
                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 flex space-x-1">
                        @if($diasComTransacoes[$day]->contains('tipo', 'receita'))
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                        @endif
                        @if($diasComTransacoes[$day]->contains('tipo', 'despesa'))
                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                        @endif
                    </div>
                @endif
            </a>
        @endfor
        </div>
    </div>
</div>