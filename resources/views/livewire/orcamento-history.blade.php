<div class="space-y-4 max-h-96 overflow-y-auto pr-2">
    @forelse ($atividades as $atividade)
        <div class="flex gap-3">
            <div class="flex-shrink-0 pt-1">
                <span class="inline-block h-2 w-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
            </div>
            <div>
                {{-- A view agora só precisa chamar nosso método formatador --}}
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    {!! $this->getFormattedDescription($atividade) !!}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Por {{ $atividade->causer->name ?? 'Sistema' }} em {{ $atividade->created_at->format('d/m/Y \à\s H:i') }}
                </p>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500">Nenhuma alteração registrada ainda.</p>
    @endforelse
</div>