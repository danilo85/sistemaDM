<div x-data="{ alertsOpen: false }">
    <button @click="alertsOpen = !alertsOpen; if(alertsOpen) { $wire.markAllAsRead() }" 
            class="relative text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white focus:outline-none">
        <svg class="h-6 w-6" :class="{'text-yellow-500': {{ $this->unreadNotifications->count() }} > 0}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        @if($this->unreadNotifications->count() > 0)
            <span class="absolute -top-2 -right-2 flex items-center justify-center h-5 w-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $this->unreadNotifications->count() }}</span>
        @endif
    </button>

    <div x-show="alertsOpen" @click.away="alertsOpen = false" class="absolute top-16 right-6 w-96 max-w-sm bg-white dark:bg-gray-700 rounded-lg shadow-lg z-50" x-transition style="display: none;">
        <div class="p-4 border-b dark:border-gray-600 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 dark:text-white">Notificações</h3>
        </div>
        <div class="max-h-96 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div class="flex items-start px-4 py-3 border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <a href="{{ $notification->data['url'] ?? '#' }}" class="flex-1">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <strong>{{ $notification->data['cliente_nome'] ?? '' }}</strong>: o orçamento <strong>{{ $notification->data['orcamento_titulo'] ?? '' }}</strong> {{ $notification->data['mensagem'] ?? '' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </a>
                    <button wire:click="deleteNotification('{{ $notification->id }}')" class="ml-4 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" title="Excluir notificação">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    Nenhuma notificação nova.
                </div>
            @endforelse
        </div>
    </div>
</div>