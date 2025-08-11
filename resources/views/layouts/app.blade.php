{{-- resources/views/layouts/app.blade.php (VERSÃO CORRETA E COMPLETA) --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Script Anti-Flicker para o Dark Mode --}}
    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
          document.documentElement.classList.add('dark');
        } else {
          document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://unpkg.com/imask"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

    {{-- Container principal com toda a lógica do Alpine.js --}}
    <div x-data="{ 
            // Lógica da Sidebar
            sidebarOpen: false, 
            sidebarCollapsed: window.innerWidth >= 1024 ? (localStorage.getItem('sidebarCollapsed') === 'true') : false,
            isMobile: window.innerWidth < 1024,
            
            // Lógica da Notificação Toast
            toastShow: false,
            toastMessage: '',
            toastType: 'success',
            toastProgress: 100,
            toastTimer: null,
            showToast({ message, type = 'success', duration = 4000 }) {
                if (this.toastTimer) clearInterval(this.toastTimer);
                this.toastMessage = message;
                this.toastType = type;
                this.toastProgress = 100;
                this.toastShow = true;
                const intervalTime = duration / 100;
                this.toastTimer = setInterval(() => {
                    this.toastProgress -= 1;
                    if (this.toastProgress <= 0) {
                        this.toastShow = false;
                        clearInterval(this.toastTimer);
                    }
                }, intervalTime);
            },
            closeToast() {
                this.toastShow = false;
                clearInterval(this.toastTimer);
            },

            // Lógica dos Alertas (Sino)
            alertsOpen: false,
            alerts: [
                // Exemplo de alerta para demonstração
                { id: 1, type: 'orcamento_aprovado', message: 'foi aprovado pelo cliente.', time: 'há 5 minutos', read: false, url: '#', clientName: 'Cliente Exemplo', budgetName: 'Criação de Logotipo', approvalDate: '06 de Ago, 2025' }
            ],
            get unreadAlertsCount() {
                return this.alerts.filter(alert => !alert.read).length;
            },
            markAllAsRead() {
                this.alerts.forEach(alert => alert.read = true);
            },
            deleteAlert(alertId) {
                this.alerts = this.alerts.filter(a => a.id !== alertId);
            }
         }" 
         x-init="
            // Lógica da Sidebar
            $watch('sidebarCollapsed', val => { if (!isMobile) { localStorage.setItem('sidebarCollapsed', val); } });
            window.addEventListener('resize', () => {
                isMobile = window.innerWidth < 1024;
                if (isMobile) { sidebarCollapsed = false; } 
                else { sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true'; }
            });
            
            // Listeners para as notificações TOAST
            @if (session('success'))
                showToast({ message: '{{ session('success') }}', type: 'success' });
            @endif
            @if (session('error'))
                showToast({ message: '{{ session('error') }}', type: 'error' });
            @endif

            Livewire.on('notify', detail => showToast({ message: detail[0].message || 'Ação concluída!', type: detail[0].type || 'success' }));
            
            // Listener para novos ALERTAS (Sino) em tempo real
            Livewire.on('new-alert', (event) => {
                alerts.unshift({
                    id: Date.now(),
                    type: event[0].type || 'info',
                    message: event[0].message || 'Você tem uma nova notificação.',
                    time: 'agora mesmo',
                    read: false,
                    url: event[0].url || '#',
                    clientName: event[0].clientName || '',
                    budgetName: event[0].budgetName || '',
                    approvalDate: event[0].approvalDate || ''
                });
            });
         " 
         class="flex h-screen bg-gray-100 dark:bg-gray-900" 
         @open-new-tab.window="window.open($event.detail.url, '_blank')">
        
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
        
        <div :class="{
                'translate-x-0 ease-out': sidebarOpen,
                '-translate-x-full ease-in': !sidebarOpen,
                'w-16': sidebarCollapsed && !sidebarOpen && !isMobile,
                'w-64': !sidebarCollapsed || sidebarOpen || isMobile
             }" 
             class="fixed inset-y-0 left-0 z-30 overflow-y-auto transition-all duration-300 transform bg-white dark:bg-gray-800 lg:translate-x-0 lg:static lg:inset-0">
            @include('layouts.sidebar')
        </div>

        <div class="flex flex-col flex-1 overflow-hidden">
            
            <header class="relative bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center flex-1">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                        @if (isset($header))
                            <div class="relative mx-4 lg:mx-0 w-full">
                                {{ $header }}
                            </div>
                        @endif
                    </div>

                    {{-- SISTEMA DE ALERTAS (SINO) APRIMORADO --}}
                    <div class="flex items-center gap-3">
                        @livewire('notification-bell')
                    </div>
                </div>

                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 dark:bg-gray-700">
                    <div x-show="toastShow" class="h-full origin-right transition-transform duration-100 ease-linear" :class="{'bg-green-500': toastType === 'success', 'bg-blue-500': toastType === 'info', 'bg-red-500': toastType === 'error'}" :style="`transform: scaleX(${toastProgress / 100})`" style="display: none;"></div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="container mx-auto px-6 py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        {{-- NOTIFICAÇÃO ESTILO TOAST --}}
        <div x-show="toastShow" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform translate-x-8" class="fixed top-24 right-6 z-50 p-4 rounded-lg shadow-lg max-w-lg w-48" :class="{'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': toastType === 'success', 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': toastType === 'info', 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': toastType === 'error'}" style="display: none;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg x-show="toastType === 'success'" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <svg x-show="toastType === 'info'" class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <svg x-show="toastType === 'error'" class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium" x-text="toastMessage"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="closeToast()" class="inline-flex rounded-md text-current opacity-70 hover:opacity-100 focus:outline-none">
                        <span class="sr-only">Fechar</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
