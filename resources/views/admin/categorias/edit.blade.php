<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Usando o componente de cabeçalho padronizado --}}
            <x-page-header>
                <x-slot:title>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('categorias.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white" title="Voltar">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                            Editar Categoria
                        </h2>
                    </div>
                </x-slot:title>
            </x-page-header>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('categorias.update', $categoria) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            {{-- Nome da Categoria --}}
                            <div>
                                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome da Categoria</label>
                                <input type="text" id="nome" name="nome" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" placeholder="Ex: Salário, Supermercado" required value="{{ old('nome', $categoria->nome) }}">
                                @error('nome')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Seletor de Tipo (Receita/Despesa) --}}
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="radio" id="tipo-receita" name="tipo" value="receita" class="hidden peer" {{ old('tipo', $categoria->tipo) == 'receita' ? 'checked' : '' }}>
                                        <label for="tipo-receita" class="flex items-center justify-center w-full p-3 text-center rounded-lg cursor-pointer transition-colors text-green-700 bg-white border border-green-300 peer-checked:bg-green-600 peer-checked:text-white dark:text-green-400 dark:bg-gray-900 dark:border-green-500 dark:peer-checked:bg-green-500">
                                            <span class="font-semibold">Receita</span>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" id="tipo-despesa" name="tipo" value="despesa" class="hidden peer" {{ old('tipo', $categoria->tipo) == 'despesa' ? 'checked' : '' }}>
                                        <label for="tipo-despesa" class="flex items-center justify-center w-full p-3 text-center rounded-lg cursor-pointer transition-colors text-red-700 bg-white border border-red-300 peer-checked:bg-red-600 peer-checked:text-white dark:text-red-400 dark:bg-gray-900 dark:border-red-500 dark:peer-checked:bg-red-500">
                                            <span class="font-semibold">Despesa</span>
                                        </label>
                                    </div>
                                </div>
                                @error('tipo')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Botões de Ação --}}
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
                            <a href="{{ route('categorias.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Atualizar Categoria
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
