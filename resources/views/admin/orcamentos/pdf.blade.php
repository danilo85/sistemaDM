<x-public-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- Cabeçalho da Proposta --}}
        <header class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-5xl font-bold text-gray-800">PROPOSTA</h1>
                <p class="text-gray-500 mt-4">Válido de {{ $orcamento->data_emissao->format('d/m/Y') }} a {{ $orcamento->data_validade->format('d/m/Y') }}</p>
                <p class="text-gray-500">Para <span class="font-bold text-gray-900">{{ $orcamento->cliente->name }}</span></p>
            </div>
            <div class="flex-shrink-0 bg-gray-800 text-white w-20 h-20 flex items-center justify-center">
                <span class="text-3xl font-bold">{{ $orcamento->numero }}</span>
            </div>
        </header>

        {{-- Corpo do Orçamento --}}
        <main>
            <div class="mb-10">
                <h2 class="font-bold text-gray-800">Orçamento: {{ $orcamento->titulo }}</h2>
                <div class="prose prose-lg max-w-none text-gray-600 mt-2 text-justify">
                    {!! nl2br(e($orcamento->descricao)) !!}
                </div>
            </div>

            <div class="mb-10">
                <p class="font-bold text-gray-800">Prazo: <span class="font-normal text-gray-600">Prazo estimado {{ $orcamento->prazo_entrega_dias }} dias úteis.</span></p>
            </div>

            {{-- Seção de Valores --}}
            <div class="bg-gray-100 rounded-lg p-8 mb-10">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500">Forma de pagamento: {{ $orcamento->condicoes_pagamento }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 uppercase">Total</p>
                        <p class="text-4xl font-bold text-gray-800">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Detalhamento de Parcelas --}}
            @if($parcelas == 2)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-800 text-white rounded-lg p-8 mb-10">
                    <div>
                        <p class="text-sm uppercase">40% PARA INICIAR</p>
                        <p class="text-3xl font-bold">1° R$ {{ number_format($orcamento->valor_total * 0.4, 2, ',', '.') }}</p>
                    </div>
                    <div class="md:text-right">
                        <p class="text-sm uppercase">60% AO TÉRMINO</p>
                        <p class="text-3xl font-bold">2° R$ {{ number_format($orcamento->valor_total * 0.6, 2, ',', '.') }}</p>
                    </div>
                </div>
            @endif

            {{-- Botões de Ação do Cliente --}}
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Ações</h3>
                <p class="text-gray-600 mt-1 mb-4">O que você gostaria de fazer em relação a esta proposta?</p>
                <div class="flex flex-wrap gap-4">
                    <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Aprovar Proposta</button>
                    <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Rejeitar</button>
                    <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Imprimir / Salvar PDF</button>
                </div>
            </div>
        </main>

        {{-- Rodapé --}}
        <footer class="mt-12 text-center text-gray-500">
            <p class="font-bold">DANILO MIGUEL</p>
            <p class="text-sm">danilomiguel.com.br | +55 14 991436268</p>
        </footer>
    </div>
    
</x-public-layout>
