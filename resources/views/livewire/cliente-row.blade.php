<tr wire:key="{{ $cliente->id }}" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
        <a href="{{ route('clientes.show', $cliente) }}" class="flex items-center gap-2 hover:underline text-indigo-600 dark:text-indigo-400">
            <span>{{ $cliente->name }}</span>
            @if (!$cliente->is_complete)
                <span class="text-yellow-500" title="Cadastro Incompleto">⚠️</span>
            @endif
        </a>
    </td>
    <td class="px-6 py-4">
        @if($cliente->email)
            <a href="mailto:{{ $cliente->email }}" class="text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                {{ $cliente->email }}
            </a>
        @else
            <span class="text-gray-400 dark:text-gray-500">-</span>
        @endif
    </td>
    <td class="px-6 py-4">
        @if($cliente->phone)
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $cliente->phone) }}" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                {{ $cliente->phone }}
            </a>
        @else
            <span class="text-gray-400 dark:text-gray-500">-</span>
        @endif
    </td>
    <td class="px-6 py-4 text-right">
       <div class="flex items-center justify-end gap-4">
            <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Editar">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
            </a>
            <form method="POST" action="{{ route('clientes.destroy', $cliente->id) }}" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Excluir">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
    </td>
</tr>
