@props(['year', 'month', 'transactionDays'])

@php
    $date = \Carbon\Carbon::createFromDate($year, $month, 1);
    $firstDayOfMonth = $date->copy()->startOfMonth();
    $daysInMonth = $date->daysInMonth;
    $startBlankDays = $firstDayOfMonth->dayOfWeek; 
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 capitalize">
                {{ $date->translatedFormat('F Y') }}
            </h3>
        </div>

        <div class="grid grid-cols-7 gap-2 text-center text-sm">
            @foreach(['D', 'S', 'T', 'Q', 'Q', 'S', 'S'] as $day)
                <div class="font-semibold text-gray-500 dark:text-gray-400 text-xs">{{ $day }}</div>
            @endforeach

            @for ($i = 0; $i < $startBlankDays; $i++)
                <div></div>
            @endfor

            @for ($day = 1; $day <= $daysInMonth; $day++)
                <a href="{{ route('transacoes.index', ['ano' => $date->year, 'mes' => $date->month, 'dia' => $day]) }}"
                   class="relative block text-center">
                    <div class="h-10 w-10 mx-auto flex items-center justify-center rounded-full transition-colors duration-200
                                {{ now()->day == $day && now()->month == $date->month && now()->year == $date->year ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-gray-700' }}">
                        {{ $day }}
                    </div>
                    @if ($transactionDays->has($day))
                        <div class="absolute bottom-1 left-1/2 -translate-x-1/2 flex space-x-1">
                            @if($transactionDays[$day]->contains('tipo', 'receita'))
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                            @endif
                            @if($transactionDays[$day]->contains('tipo', 'despesa'))
                                <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                            @endif
                        </div>
                    @endif
                </a>
            @endfor
        </div>
    </div>
</div>