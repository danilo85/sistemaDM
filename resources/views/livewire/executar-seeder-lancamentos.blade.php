<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-xl p-6 text-white">
            <h1 class="text-2xl font-bold">Executar Seeder de Lançamentos</h1>
            <p class="text-blue-100 mt-2">Criar 20 lançamentos financeiros de exemplo (10 bancários + 10 de cartão)</p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-b-xl shadow-lg p-6">
            @if(!$executado)
                <div class="text-center">
                    <div class="mb-6">
                        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Criar Lançamentos de Exemplo</h2>
                        <p class="text-gray-600 mb-6">Este seeder irá criar:</p>
                        
                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h3 class="font-semibold text-green-800 mb-2">10 Lançamentos Bancários</h3>
                                <ul class="text-sm text-green-700 space-y-1">
                                    <li>• Receitas: Salário, freelance, vendas</li>
                                    <li>• Despesas: Supermercado, contas, combustível</li>
                                    <li>• Status: Confirmado e pago</li>
                                </ul>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h3 class="font-semibold text-blue-800 mb-2">10 Lançamentos de Cartão</h3>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Compras online, restaurantes</li>
                                    <li>• Assinaturas, entretenimento</li>
                                    <li>• Status: Pendente (fatura)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <button 
                        wire:click="executarSeeder" 
                        class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg"
                    >
                        <span wire:loading.remove>Executar Seeder</span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Executando...
                        </span>
                    </button>
                </div>
            @endif

            @if($mensagem)
                <div class="mt-6">
                    @if($erro)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-red-800 font-medium">Erro</span>
                            </div>
                            <p class="text-red-700 mt-2">{{ $mensagem }}</p>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-green-800 font-medium">Sucesso</span>
                            </div>
                            <p class="text-green-700 mt-2">{{ $mensagem }}</p>
                            
                            <div class="mt-4 flex space-x-3">
                                <a href="{{ route('transacoes.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                    Ver Lançamentos
                                </a>
                                <button 
                                    wire:click="$set('executado', false)" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                                >
                                    Executar Novamente
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Info adicional -->
            <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-yellow-800 font-medium">Informações Importantes</h3>
                        <ul class="text-yellow-700 text-sm mt-1 space-y-1">
                            <li>• Certifique-se de ter bancos e cartões cadastrados</li>
                            <li>• Certifique-se de ter categorias criadas</li>
                            <li>• Os lançamentos terão datas dos últimos 30 dias</li>
                            <li>• Lançamentos bancários serão marcados como pagos</li>
                            <li>• Lançamentos de cartão ficarão pendentes (fatura)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>