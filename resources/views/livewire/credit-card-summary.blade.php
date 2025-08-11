{{-- CORREÇÃO: O componente agora é uma linha de tabela (tr) para funcionar corretamente dentro da lista --}}
<tr x-data="{ expanded: false }" class="bg-gray-50 dark:bg-gray-900/50 border-b dark:border-gray-700">
    <td colspan="6" class="p-0">
        <div class="p-4">
            {{-- Cabeçalho do Card --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 flex-grow cursor-pointer" @click="expanded = !expanded">
                    @if($fatura->cartao->bandeira_path)
                        <img src="{{ $fatura->cartao->bandeira_path }}" alt="Bandeira" class="h-8 rounded-md">
                    @endif
                    <div>
                        <div class="font-bold text-gray-800 dark:text-gray-200">{{ $fatura->cartao->nome }}</div>
                        <div class="text-xs text-gray-500">{{ count($fatura->transacoes) }} despesas na fatura</div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="font-mono text-lg font-semibold text-red-500">- R$ {{ number_format($fatura->total, 2, ',', '.') }}</div>
                        <div class="text-xs text-gray-500">Total da Fatura</div>
                    </div>
                    @php
                        $faturaEstaPaga = $fatura->transacoes->every(fn($t) => $t->pago);
                    @endphp

                    <button wire:click="pagarFatura" 
                            class="ml-4 px-3 py-1 text-xs font-medium text-white rounded-full 
                                   {{ !$faturaEstaPaga ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 hover:bg-gray-500' }}">
                        {{ !$faturaEstaPaga ? 'Pagar Fatura' : 'Desfazer' }}
                    </button>
                    <button class="text-gray-400 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" @click="expanded = !expanded">
                        <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        <svg x-show="expanded" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Detalhes da Fatura (que expandem/recolhem) --}}
            <div x-show="expanded" x-transition class="mt-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        {{-- O cabeçalho da sub-tabela não é necessário, pois já temos o principal --}}
                        <tbody>
                            @foreach($fatura->transacoes as $transacao)
                                @livewire('transacao-item', ['transacao' => $transacao, 'isDetalheFatura' => true], key('fatura-'.$transacao->id))
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </td>
</tr>
