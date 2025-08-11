<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalhes do Cliente: {{ $cliente->name }}
            </h2>
            <a href="{{ route('clientes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-300 font-bold py-2 px-4 rounded-lg text-sm">
                &larr; Voltar para a Lista
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Aqui chamamos o nosso componente Livewire que mostra o extrato --}}
            @livewire('cliente.cliente-detalhes', ['cliente' => $cliente])
        </div>
    </div>
</x-app-layout>
