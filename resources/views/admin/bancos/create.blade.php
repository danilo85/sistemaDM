<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header>
                <x-slot:title>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('bancos.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white" title="Voltar">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                            Adicionar Nova Conta Bancária
                        </h2>
                    </div>
                </x-slot:title>
            </x-page-header>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    {{-- A página agora carrega o componente Livewire, passando os dados necessários --}}
                    @livewire('banco-form', [
                        'bancosDisponiveis' => $bancosDisponiveis
                    ])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
