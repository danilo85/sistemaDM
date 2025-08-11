<div class="space-y-6">
    {{-- Cards de Estatísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Card 1: Faturamento do Mês --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center gap-6">
            <div class="bg-blue-100 dark:bg-blue-900/50 p-4 rounded-lg">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01v.01M12 18v-1m0-1v.01m0-1V14m0 2.01v.01M12 20v-1m0-1v.01m0-1V18m0 2.01v.01M12 4h.01M12 20h.01M4 12h.01M20 12h.01M6.343 17.657h.01M17.657 6.343h.01M6.343 6.343h.01M17.657 17.657h.01"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Faturamento do Mês</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">R$ {{ number_format($faturamentoMes, 2, ',', '.') }}</p>
            </div>
        </div>

        {{-- Card 2: Despesas do Mês --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center gap-6">
            <div class="bg-red-100 dark:bg-red-900/50 p-4 rounded-lg">
                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Despesas do Mês</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">R$ {{ number_format($despesasMes, 2, ',', '.') }}</p>
            </div>
        </div>

        {{-- Card 3: Saldo do Mês --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center gap-6">
            <div class="bg-green-100 dark:bg-green-900/50 p-4 rounded-lg">
                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Saldo do Mês</p>
                <p class="text-2xl font-bold {{ $saldoMes >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    R$ {{ number_format($saldoMes, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Card 4: Total a Receber --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center gap-6">
            <div class="bg-purple-100 dark:bg-purple-900/50 p-4 rounded-lg">
                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total a Receber</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">R$ {{ number_format($totalAReceber, 2, ',', '.') }}</p>
            </div>
        </div>

        {{-- Card 5: Orçamentos Pendentes --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center gap-6">
            <div class="bg-yellow-100 dark:bg-yellow-900/50 p-4 rounded-lg">
                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Orçamentos Pendentes</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $orcamentosPendentes }}</p>
            </div>
        </div>

        {{-- Card 6: Projetos em Andamento --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex items-center gap-6">
            <div class="bg-indigo-100 dark:bg-indigo-900/50 p-4 rounded-lg">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Projetos em Andamento</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $projetosEmAndamento }}</p>
            </div>
        </div>
    </div>

    {{-- Atividades Recentes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Orçamentos Recentes --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Orçamentos Recentes</h3>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orcamentosRecentes as $orcamento)
                    <li class="py-3">
                        <a href="{{ route('orcamentos.show', $orcamento) }}" class="flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700/50 p-2 rounded-lg -m-2">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $orcamento->titulo }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $orcamento->cliente->name }}</p>
                            </div>
                            <span class="text-sm font-mono text-gray-600 dark:text-gray-300">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</span>
                        </a>
                    </li>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum orçamento recente.</p>
                @endforelse
            </ul>
        </div>
        {{-- Lançamentos Recentes --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Lançamentos Recentes</h3>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transacoesRecentes as $transacao)
                    <li class="py-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $transacao->descricao }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transacao->data->format('d/m/Y') }}</p>
                            </div>
                            <span class="text-sm font-mono {{ $transacao->tipo == 'receita' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transacao->tipo == 'receita' ? '+' : '-' }} R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                            </span>
                        </div>
                    </li>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum lançamento recente.</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>
