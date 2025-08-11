@props(['status'])

@php
    $colors = match (strtolower($status)) {
        'analisando' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'finalizado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'rejeitado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'pago' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    };
@endphp

<span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full {{ $colors }}">
    {{ $status }}
</span>