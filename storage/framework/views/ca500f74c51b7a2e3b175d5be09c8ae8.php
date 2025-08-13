<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
    
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
        <?php echo e($title); ?>

    </h2>

    
    <div class="flex items-center space-x-4">
        
         <div class="relative" x-data="{ alertsOpen: false }">
             <button @click="alertsOpen = !alertsOpen" class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                 </svg>
                 <span x-show="$parent.unreadAlertsCount > 0" x-text="$parent.unreadAlertsCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
             </button>
             
             
             <div x-show="alertsOpen" @click.away="alertsOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                 <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                     <div class="flex justify-between items-center">
                         <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Notificações</h3>
                         <button @click="$parent.markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Marcar todas como lidas</button>
                     </div>
                 </div>
                 
                 <div class="max-h-64 overflow-y-auto">
                     <template x-for="alert in $parent.alerts" :key="alert.id">
                         <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" :class="{ 'bg-blue-50 dark:bg-blue-900/20': !alert.read }">
                             <div class="flex justify-between items-start">
                                 <div class="flex-1">
                                     <div class="flex items-center space-x-2">
                                         <div class="w-2 h-2 bg-blue-500 rounded-full" x-show="!alert.read"></div>
                                         <p class="text-sm text-gray-900 dark:text-gray-100">
                                             <span class="font-medium" x-text="alert.clientName"></span>
                                             <span x-text="alert.message"></span>
                                         </p>
                                     </div>
                                     <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="alert.time"></p>
                                 </div>
                                 <button @click="$parent.deleteAlert(alert.id)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                     </svg>
                                 </button>
                             </div>
                         </div>
                     </template>
                     
                     <div x-show="$parent.alerts.length === 0" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                         <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                         </svg>
                         <p class="text-sm">Nenhuma notificação</p>
                     </div>
                 </div>
             </div>
         </div>

        
        <button @click="darkMode = !darkMode" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
            <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>

        
        <div class="relative" x-data="{ profileOpen: false }">
            <button @click="profileOpen = !profileOpen" class="flex items-center p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </button>
            
            <div x-show="profileOpen" @click.away="profileOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    Editar Perfil
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Sair
                    </button>
                </form>
            </div>
        </div>

        
        <?php if(isset($actions)): ?>
            <div class="ml-4">
                <?php echo e($actions); ?>

            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\laragon\www\sistemaDM\resources\views/components/page-header.blade.php ENDPATH**/ ?>