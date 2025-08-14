<x-app-layout>
    {{-- Barra com título ocupando 100% da largura --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white w-full">
        <div class="px-4 sm:px-6 lg:px-8 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold">Lançamentos Financeiros</h1>
                    <p class="text-blue-100 mt-1 text-sm">Visualize e gerencie todas as suas receitas e despesas</p>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-8 h-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                {{-- A página agora carrega o componente Livewire sem padding extra --}}
                @livewire('transacao-list')
            </div>
        </div>
    </div>

    {{-- BOTÃO DE AÇÃO FLUTUANTE (FAB) --}}
    {{-- CORREÇÃO: Adicionada a classe z-40 para garantir que o botão fique acima do rodapé (que tem z-30) --}}
    <a href="{{ route('transacoes.create') }}" title="Novo Lançamento"
       class="fixed bottom-6 right-6 z-40 inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform duration-200 ease-in-out hover:scale-110">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
    </a>
</x-app-layout>
