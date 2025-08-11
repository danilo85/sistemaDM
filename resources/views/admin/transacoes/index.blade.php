<x-app-layout>
    <x-slot name="header">
        {{-- Container para alinhar o título com o conteúdo --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Lançamentos Financeiros') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Visualize e gira todas as suas receitas e despesas.
            </p>
        </div>
    </x-slot>

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
