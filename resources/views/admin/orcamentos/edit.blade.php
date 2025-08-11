<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header>
                <x-slot:title>
                    <div>
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Editar Orçamento #{{ $orcamento->numero }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Ajuste os detalhes da proposta abaixo.
                        </p>
                    </div>
                </x-slot:title>
                <x-slot:actions>
                    <a href="{{ route('orcamentos.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        Voltar para a Lista
                    </a>
                </x-slot:actions>
            </x-page-header>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    {{-- Chamando nosso componente inteligente --}}
                    @livewire('orcamento-edit-form', ['orcamento' => $orcamento])

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
