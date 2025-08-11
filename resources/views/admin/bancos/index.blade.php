<x-app-layout>
    <x-slot name="header">
        {{-- Container para alinhar o título com o conteúdo --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Contas Bancárias') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Logo</th>
                                    <th scope="col" class="px-6 py-3">Nome da Conta</th>
                                    <th scope="col" class="px-6 py-3">Tipo</th>
                                    <th scope="col" class="px-6 py-3 text-right">Saldo Inicial</th>
                                    <th scope="col" class="px-6 py-3 text-right">Saldo Atual</th>
                                    <th scope="col" class="px-6 py-3 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bancos as $banco)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">
                                            @if($banco->logo_path)
                                                <img src="{{ $banco->logo_path }}" alt="Logo {{ $banco->nome }}" class="h-10 object-contain full">
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $banco->nome }}
                                        </td>
                                        <td class="px-6 py-4 capitalize">
                                            {{ $banco->tipo_conta }}
                                        </td>
                                        {{-- Saldo com fonte maior e sem quebra de linha --}}
                                        <td class="px-6 py-4 text-right text-lg text-gray-600 dark:text-gray-400">
                                            <span class="whitespace-nowrap">R$ {{ number_format($banco->saldo_inicial, 2, ',', '.') }}</span>
                                        </td>
                                        {{-- Saldo atual com mais destaque e sem quebra de linha --}}
                                        <td class="px-6 py-4 text-right text-xl font-bold {{ $banco->saldo_atual >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <span class="whitespace-nowrap">R$ {{ number_format($banco->saldo_atual, 2, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-4">
                                                <a href="{{ route('bancos.edit', $banco->id) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                                </a>
                                                <form method="POST" action="{{ route('bancos.destroy', $banco->id) }}" onsubmit="return confirm('Tem certeza que deseja excluir esta conta?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Excluir">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">
                                            Nenhuma conta bancária encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- BOTÃO DE AÇÃO FLUTUANTE (FAB) --}}
    <a href="{{ route('bancos.create') }}" title="Nova Conta"
       class="fixed bottom-6 right-6 inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform duration-200 ease-in-out hover:scale-110">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
    </a>
</x-app-layout>
