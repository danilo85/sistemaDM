@props([
    'status' => 'default',
    'size' => 'sm', // sm, md, lg
    'variant' => 'filled' // filled, outlined
])

@php
    // Mapeia status para cores
    $statusColors = [
        'aprovado' => 'green',
        'pendente' => 'yellow', 
        'rejeitado' => 'red',
        'rascunho' => 'gray',
        'enviado' => 'blue',
        'em_andamento' => 'indigo',
        'concluido' => 'green',
        'cancelado' => 'red',
        'ativo' => 'green',
        'inativo' => 'gray',
        'pago' => 'green',
        'em_atraso' => 'red',
        'vencido' => 'red',
        'default' => 'gray'
    ];
    
    // Mapeia status para textos legíveis
    $statusTexts = [
        'aprovado' => 'Aprovado',
        'pendente' => 'Pendente',
        'rejeitado' => 'Rejeitado', 
        'rascunho' => 'Rascunho',
        'enviado' => 'Enviado',
        'em_andamento' => 'Em Andamento',
        'concluido' => 'Concluído',
        'cancelado' => 'Cancelado',
        'ativo' => 'Ativo',
        'inativo' => 'Inativo',
        'pago' => 'Pago',
        'em_atraso' => 'Em Atraso',
        'vencido' => 'Vencido'
    ];
    
    // Normaliza o status para lowercase e substitui espaços por underscore
    $normalizedStatus = strtolower(str_replace([' ', '-'], '_', $status));
    
    // Obtém a cor e texto do status
    $color = $statusColors[$normalizedStatus] ?? $statusColors['default'];
    $text = $statusTexts[$normalizedStatus] ?? ucfirst($status);
    
    // Define classes de tamanho
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['sm'];
    
    // Define classes de variante
    if ($variant === 'outlined') {
        $variantClasses = "border border-{$color}-200 text-{$color}-800 bg-transparent dark:border-{$color}-800 dark:text-{$color}-200";
    } else {
        $variantClasses = "bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200";
    }
    
    // Classe final
    $classes = "inline-flex items-center {$sizeClass} rounded-full font-medium {$variantClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($status === 'aprovado' || $status === 'Aprovado')
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
        </svg>
    @elseif($status === 'pendente' || $status === 'Pendente')
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
        </svg>
    @elseif($status === 'rejeitado' || $status === 'Rejeitado')
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    @elseif($status === 'pago' || $status === 'Pago')
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
        </svg>
    @endif
    {{ $text }}
</span>