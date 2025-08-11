<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
    {{-- O Título da Página --}}
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
        {{ $title }}
    </h2>

    {{-- A Área para os Botões de Ação --}}
    @if (isset($actions))
        <div>
            {{ $actions }}
        </div>
    @endif
</div>