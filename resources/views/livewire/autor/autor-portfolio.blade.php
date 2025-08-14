<div class="space-y-8">
    {{-- Secção 1: Cabeçalho do Autor e KPIs --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <div class="flex-shrink-0 w-24 h-24 rounded-full overflow-hidden">
                @if($autor->logo)
                    <img src="{{ Storage::url($autor->logo) }}" alt="{{ $autor->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <span class="text-3xl font-bold text-gray-500">{{ substr($autor->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-grow">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $autor->name }}</h2>
                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $autor->bio }}</p>
                
                {{-- KPIs ATUALIZADOS --}}
                <div class="mt-4 flex flex-wrap gap-6 text-sm">
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 4h5m-5 4h5"></path></svg>
                        <span><span class="font-bold">{{ $totalProjetos }}</span> Projetos Participados</span>
                    </div>
                    {{-- NOVO CARD DE VALOR TOTAL --}}
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01v.01M12 18v-1m0-1v.01m0-1V14m0 2.01v.01M12 20v-1m0-1v.01m0-1V18m0 2.01v.01M12 4h.01M12 20h.01M4 12h.01M20 12h.01M6.343 17.657h.01M17.657 6.343h.01M6.343 6.343h.01M17.657 17.657h.01"></path></svg>
                        <span>Valor Gerado: <span class="font-bold">R$ {{ number_format($valorTotalProjetos, 2, ',', '.') }}</span></span>
                    </div>
                    @if($principalParceiro)
                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>Principal Parceiro: <span class="font-bold">{{ $principalParceiro->name }}</span></span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Secção 2: Linha do Tempo de Projetos --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Linha do Tempo de Projetos</h3>
        </div>
        
        <div class="relative">
            {{-- Linha vertical moderna --}}
            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-500 via-purple-500 to-pink-500 opacity-30"></div>
            
            @forelse($orcamentos as $orcamento)
                <div class="relative flex items-start gap-6 pb-8 last:pb-0">
                    {{-- Indicador moderno --}}
                    <div class="relative z-10 flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        {{-- Linha de conexão --}}
                        <div class="absolute top-12 left-1/2 transform -translate-x-1/2 w-0.5 h-8 bg-gradient-to-b from-blue-500 to-transparent opacity-30 last:hidden"></div>
                    </div>
                    
                    {{-- Card do projeto modernizado --}}
                    <div class="flex-1">
                        <a href="{{ route('orcamentos.show', ['orcamento' => $orcamento, 'back_url' => url()->current()]) }}" class="block group">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                                {{-- Cabeçalho do card --}}
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $orcamento->created_at->format('d/m/Y') }}
                                            </span>
                                            <x-status-badge :status="$orcamento->status" />
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $orcamento->titulo }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center gap-1 mt-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $orcamento->cliente->name }}
                                        </p>
                                    </div>
                                    
                                    {{-- Valor e status financeiro --}}
                                    <div class="text-right">
                                        <p class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                            R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                        </p>
                                        @php
                                            $faltaPagar = $orcamento->valor_total - $orcamento->pagamentos_sum_valor_pago;
                                        @endphp
                                        <div class="mt-1">
                                            @if($faltaPagar > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Pendente: R$ {{ number_format($faltaPagar, 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    ✓ Quitado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Colaboradores modernizados --}}
                                @if($orcamento->autores->count() > 1)
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Colaboradores:</span>
                                        <div class="flex items-center -space-x-2">
                                            @foreach($orcamento->autores as $colaborador)
                                                @if($colaborador->id !== $autor->id)
                                                    <div class="relative group/avatar">
                                                        @if($colaborador->logo)
                                                            <img src="{{ Storage::url($colaborador->logo) }}" alt="{{ $colaborador->name }}" class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 object-cover">
                                                        @else
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white border-2 border-white dark:border-gray-800">
                                                                {{ substr($colaborador->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded px-2 py-1 opacity-0 group-hover/avatar:opacity-100 transition-opacity whitespace-nowrap z-10">
                                                            {{ $colaborador->name }}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-center text-gray-500 dark:text-gray-400 text-lg font-medium">Nenhum projeto encontrado</p>
                    <p class="text-center text-gray-400 dark:text-gray-500 text-sm mt-1">Este autor ainda não participou de nenhum projeto.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
