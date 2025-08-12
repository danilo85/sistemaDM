<div class="relative" x-data="{ open: @entangle('showDropdown'), settings: @entangle('showSettings') }">
    <!-- Botão do Sino -->
    <button 
        @click="open = !open" 
        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg transition-colors duration-200"
        :class="{ 'text-blue-600': open }"
    >
        <!-- Ícone de Sino -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Badge de contagem -->
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[1.125rem] h-[1.125rem] text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown de Notificações -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="open = false"
        class="absolute right-0 z-50 mt-2 w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Notificações</h3>
                <div class="flex items-center space-x-2">
                    <!-- Botão de Configurações -->
                    <button 
                        @click="settings = !settings"
                        class="p-1 text-gray-400 hover:text-gray-600 rounded transition-colors"
                        title="Configurações"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>
                    
                    <!-- Marcar todas como lidas -->
                    @if($unreadCount > 0)
                        <button 
                            wire:click="markAllAsRead"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
                            title="Marcar todas como lidas"
                        >
                            Marcar todas
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="mt-3 flex space-x-2">
                <select wire:model.live="selectedPriority" wire:change="updateFilters" class="text-xs border-gray-300 rounded px-2 py-1">
                    <option value="all">Todas prioridades</option>
                    <option value="high">Alta</option>
                    <option value="medium">Média</option>
                    <option value="low">Baixa</option>
                </select>
                
                <select wire:model.live="selectedType" wire:change="updateFilters" class="text-xs border-gray-300 rounded px-2 py-1">
                    <option value="all">Todos tipos</option>
                    <option value="budget_approved">Aprovado</option>
                    <option value="budget_rejected">Rejeitado</option>
                    <option value="deadline_approaching">Prazo próximo</option>
                    <option value="viewed_no_action">Sem ação</option>
                </select>
            </div>
        </div>

        <!-- Painel de Configurações -->
        <div x-show="settings" x-transition class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Configurações de Notificação</h4>
            
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-600">Dias após visualização:</label>
                    <input 
                        type="number" 
                        wire:model.blur="settings.days_after_view"
                        wire:change="updateSettings('days_after_view', $event.target.value)"
                        class="w-full text-xs border-gray-300 rounded px-2 py-1 mt-1"
                        min="1" max="30"
                    >
                </div>
                
                <div>
                    <label class="text-xs text-gray-600">Dias antes do prazo:</label>
                    <input 
                        type="number" 
                        wire:model.blur="settings.days_before_deadline"
                        wire:change="updateSettings('days_before_deadline', $event.target.value)"
                        class="w-full text-xs border-gray-300 rounded px-2 py-1 mt-1"
                        min="1" max="30"
                    >
                </div>
                
                <div>
                    <label class="text-xs text-gray-600">Ordem de prioridade:</label>
                    <select 
                        wire:model.blur="settings.priority_order"
                        wire:change="updateSettings('priority_order', $event.target.value)"
                        class="w-full text-xs border-gray-300 rounded px-2 py-1 mt-1"
                    >
                        <option value="high_first">Alta primeiro</option>
                        <option value="low_first">Baixa primeiro</option>
                        <option value="chronological">Cronológica</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lista de Notificações -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ !$notification['is_read'] ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start space-x-3">
                        <!-- Ícone -->
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center {{ $this->getNotificationColor($notification['priority']) }}">
                                @php
                                    $icon = $this->getNotificationIcon($notification['type']);
                                @endphp
                                
                                @if($icon === 'check-circle')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($icon === 'x-circle')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($icon === 'clock')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($icon === 'eye')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h10a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Conteúdo -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $notification['title'] }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $this->getPriorityBadgeClass($notification['priority']) }}">
                                    {{ ucfirst($notification['priority']) }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                            
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500">{{ $this->getTimeAgo($notification['created_at']) }}</span>
                                
                                <!-- Ações -->
                                <div class="flex items-center space-x-2">
                                    @if($notification['budget_id'])
                                        <button 
                                            wire:click="openBudget({{ $notification['id'] }}, {{ $notification['budget_id'] }})"
                                            class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                                            title="Abrir orçamento"
                                        >
                                            Abrir
                                        </button>
                                    @endif
                                    
                                    @if(!$notification['is_read'])
                                        <button 
                                            wire:click="markAsRead({{ $notification['id'] }})"
                                            class="text-xs text-green-600 hover:text-green-800 font-medium"
                                            title="Marcar como lida"
                                        >
                                            Lida
                                        </button>
                                    @endif
                                    
                                    <!-- Dropdown de Snooze -->
                                    <div class="relative" x-data="{ snoozeOpen: false }">
                                        <button 
                                            @click="snoozeOpen = !snoozeOpen"
                                            class="text-xs text-gray-500 hover:text-gray-700 font-medium"
                                            title="Adiar notificação"
                                        >
                                            Adiar
                                        </button>
                                        
                                        <div 
                                            x-show="snoozeOpen" 
                                            x-transition
                                            @click.away="snoozeOpen = false"
                                            class="absolute right-0 z-10 mt-1 w-32 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                                        >
                                            <div class="py-1">
                                                <button 
                                                    wire:click="snoozeNotification({{ $notification['id'] }}, 1)"
                                                    @click="snoozeOpen = false"
                                                    class="block w-full text-left px-3 py-1 text-xs text-gray-700 hover:bg-gray-100"
                                                >
                                                    1 hora
                                                </button>
                                                <button 
                                                    wire:click="snoozeNotification({{ $notification['id'] }}, 24)"
                                                    @click="snoozeOpen = false"
                                                    class="block w-full text-left px-3 py-1 text-xs text-gray-700 hover:bg-gray-100"
                                                >
                                                    1 dia
                                                </button>
                                                <button 
                                                    wire:click="snoozeNotification({{ $notification['id'] }}, 168)"
                                                    @click="snoozeOpen = false"
                                                    class="block w-full text-left px-3 py-1 text-xs text-gray-700 hover:bg-gray-100"
                                                >
                                                    1 semana
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Excluir -->
                                    <button 
                                        wire:click="deleteNotification({{ $notification['id'] }})"
                                        class="text-xs text-red-500 hover:text-red-700 font-medium"
                                        title="Excluir notificação"
                                        onclick="return confirm('Tem certeza que deseja excluir esta notificação?')"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h10a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma notificação</h3>
                    <p class="mt-1 text-sm text-gray-500">Você está em dia com tudo!</p>
                </div>
            @endforelse
        </div>
        
        @if($this->notifications->count() > 0)
            <!-- Footer com paginação -->
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">
                        Mostrando {{ $this->notifications->count() }} de {{ $totalNotifications }} notificações
                    </span>
                    
                    @if($this->notifications->hasPages())
                        <div class="flex space-x-1">
                            @if($this->notifications->onFirstPage())
                                <span class="px-2 py-1 text-xs text-gray-400 cursor-not-allowed">Anterior</span>
                            @else
                                <button 
                                    wire:click="previousPage"
                                    class="px-2 py-1 text-xs text-blue-600 hover:text-blue-800"
                                >
                                    Anterior
                                </button>
                            @endif
                            
                            @if($this->notifications->hasMorePages())
                                <button 
                                    wire:click="nextPage"
                                    class="px-2 py-1 text-xs text-blue-600 hover:text-blue-800"
                                >
                                    Próxima
                                </button>
                            @else
                                <span class="px-2 py-1 text-xs text-gray-400 cursor-not-allowed">Próxima</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>