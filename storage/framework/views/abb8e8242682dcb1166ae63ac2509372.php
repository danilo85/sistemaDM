

<?php
    // Classes base reutilizáveis (DRY)
    $linkBase = 'flex items-center rounded-lg text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700';
    $activeBg = 'bg-gray-100 dark:bg-gray-700';
?>

<aside
    x-data="{
        sidebarCollapsed: false,
        isMobile: false,
        sidebarOpen: true,
        darkMode: document.documentElement.classList.contains('dark') ?? false,
        init() {
            // Responsivo (smaller than md)
            const mq = window.matchMedia('(max-width: 1024px)');
            const setMobile = () => { this.isMobile = mq.matches; if(this.isMobile) this.sidebarCollapsed = false; };
            mq.addEventListener?.('change', setMobile); setMobile();

            // Persistência de colapso
            const saved = localStorage.getItem('sidebarCollapsed');
            if (saved !== null) this.sidebarCollapsed = JSON.parse(saved);
            this.$watch('sidebarCollapsed', v => localStorage.setItem('sidebarCollapsed', JSON.stringify(v)));

            // DarkMode (opcional)
            this.$watch('darkMode', v => {
                document.documentElement.classList.toggle('dark', v);
                localStorage.setItem('theme', v ? 'dark' : 'light');
            });
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) this.darkMode = savedTheme === 'dark';
        }
    }"
    x-cloak
    class="flex h-screen flex-col overflow-y-auto overflow-x-hidden border-r bg-white dark:border-gray-700 dark:bg-gray-800"
    :class="(sidebarCollapsed && !isMobile) ? 'w-16 px-2 py-8' : 'w-64 px-5 py-8'"
    aria-label="Barra lateral de navegação"
    :aria-expanded="(!sidebarCollapsed || isMobile) ? 'true' : 'false'"
>
    
    <div class="flex items-center justify-between">
        <a href="<?php echo e(route('dashboard')); ?>" x-show="!sidebarCollapsed || isMobile" class="focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded">
            <?php if(Auth::user()->logo_path): ?>
                <img
                    src="<?php echo e(asset('storage/' . Auth::user()->logo_path)); ?>"
                    alt="Logo"
                    class="h-10 w-auto"
                    loading="lazy"
                    decoding="async"
                >
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'h-10 w-auto fill-current text-gray-800 dark:text-gray-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-10 w-auto fill-current text-gray-800 dark:text-gray-200']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
            <?php endif; ?>
        </a>

        
        <button
            @click="isMobile ? (sidebarOpen = false) : (sidebarCollapsed = !sidebarCollapsed)"
            class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            :class="sidebarCollapsed && !isMobile ? 'mx-auto mt-2' : ''"
            :aria-label="isMobile ? 'Fechar menu' : (sidebarCollapsed ? 'Expandir menu' : 'Colapsar menu')"
            type="button"
        >
            
            <svg x-show="isMobile" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            
            <svg x-show="!isMobile" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="sidebarCollapsed ? 'M13 5l7 7-7 7M5 5l7 7-7 7' : 'M11 19l-7-7 7-7M5 12h14'"/>
            </svg>
        </button>
    </div>

    <div class="mt-6 flex flex-1 flex-col justify-between">
        <nav class="-mx-3 space-y-6" role="navigation">
            
            <div class="space-y-1">
                <a
                    class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('dashboard') ? $activeBg : ''); ?>"
                    :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                    href="<?php echo e(route('dashboard')); ?>"
                    :title="sidebarCollapsed ? 'Dashboard' : ''"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Dashboard</span>
                </a>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acessar_orcamentos')): ?>
                    <a
                        class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('orcamentos.*') ? $activeBg : ''); ?>"
                        :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                        href="<?php echo e(route('orcamentos.index')); ?>"
                        :title="sidebarCollapsed ? 'Orçamentos' : ''"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M16 8.99992L12.5 15.5L11 12.5L8 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="17" cy="6" r="1" stroke="currentColor" stroke-width="2"/>
                            <path d="M3 10C3 7.23858 5.23858 5 8 5H16C18.7614 5 21 7.23858 21 10V18C21 20.7614 18.7614 23 16 23H8C5.23858 23 3 20.7614 3 18V10Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Orçamentos</span>
                    </a>
                <?php endif; ?>
            </div>

            
            <?php if(auth()->user()->can('acessar_clientes') || auth()->user()->can('acessar_autores') || auth()->user()->can('gerenciar_usuarios')): ?>
                <div class="space-y-1 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <label x-show="!sidebarCollapsed || isMobile" class="px-3 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Gestão</label>
                    <div x-show="sidebarCollapsed && !isMobile" class="w-full h-px bg-gray-300 dark:bg-gray-600 mx-auto"></div>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('gerenciar_usuarios')): ?>
                        <a
                            class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('admin.users.*') ? $activeBg : ''); ?>"
                            :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                            href="<?php echo e(route('admin.users.index')); ?>"
                            :title="sidebarCollapsed ? 'Usuários' : ''"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Usuários</span>
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acessar_clientes')): ?>
                        <a
                            class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('clientes.*') ? $activeBg : ''); ?>"
                            :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                            href="<?php echo e(route('clientes.index')); ?>"
                            :title="sidebarCollapsed ? 'Clientes' : ''"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Clientes</span>
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acessar_autores')): ?>
                        <a
                            class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('autores.*') ? $activeBg : ''); ?>"
                            :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                            href="<?php echo e(route('autores.index')); ?>"
                            :title="sidebarCollapsed ? 'Autores' : ''"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M15 12H18M15 8H18M15 16H18M11.5 8C11.5 6.61929 10.3807 5.5 9 5.5C7.61929 5.5 6.5 6.61929 6.5 8C6.5 9.38071 7.61929 10.5 9 10.5C10.3807 10.5 11.5 9.38071 11.5 8ZM9 12.5C6.23858 12.5 4 14.7386 4 17.5V18.5H14V17.5C14 14.7386 11.7614 12.5 9 12.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Autores</span>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            
            <div class="space-y-1 border-t border-gray-200 dark:border-gray-700 pt-4">
                <label x-show="!sidebarCollapsed || isMobile" x-cloak class="px-3 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Portfólio</label>
                <div x-show="sidebarCollapsed && !isMobile" class="w-full h-px bg-gray-300 dark:bg-gray-600 mx-auto"></div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acessar_clientes')): ?>
                    
                    <a class="flex items-center rounded-lg text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 <?php echo e(request()->routeIs('portfolio.index') ? 'bg-gray-100 dark:bg-gray-700' : ''); ?>" 
                    :class="(sidebarCollapsed && !isMobile) ? 'p-3 justify-center' : 'p-2'" 
                    href="<?php echo e(route('portfolio.index')); ?>" 
                    :title="sidebarCollapsed ? 'Meus Trabalhos' : ''">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span x-show="!sidebarCollapsed || isMobile" x-cloak class="mx-2 text-sm font-medium whitespace-nowrap">Meus Trabalhos</span>
                    </a>

                    
                    <a class="flex items-center rounded-lg text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 <?php echo e(request()->routeIs('portfolio.pipeline.index') ? 'bg-gray-100 dark:bg-gray-700' : ''); ?>" 
                    :class="(sidebarCollapsed && !isMobile) ? 'p-3 justify-center' : 'p-2'" 
                    href="<?php echo e(route('portfolio.pipeline.index')); ?>" 
                    :title="sidebarCollapsed ? 'Pipeline' : ''">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        <span x-show="!sidebarCollapsed || isMobile" x-cloak class="mx-2 text-sm font-medium whitespace-nowrap">Pipeline</span>
                    </a>

                    
                    <a class="flex items-center rounded-lg text-gray-600 transition-colors duration-300 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-200 dark:hover:bg-gray-700 <?php echo e(request()->routeIs('portfolio.categories.index') ? 'bg-gray-100 dark:bg-gray-700' : ''); ?>" 
                    :class="(sidebarCollapsed && !isMobile) ? 'p-3 justify-center' : 'p-2'" 
                    href="<?php echo e(route('portfolio.categories.index')); ?>" 
                    :title="sidebarCollapsed ? 'Categorias' : ''">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span x-show="!sidebarCollapsed || isMobile" x-cloak class="mx-2 text-sm font-medium whitespace-nowrap">Categorias</span>
                    </a>
                <?php endif; ?>
            </div>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acessar_financeiro')): ?>
                <div class="space-y-1 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <label x-show="!sidebarCollapsed || isMobile" class="px-3 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Financeiro</label>
                    <div x-show="sidebarCollapsed && !isMobile" class="w-full h-px bg-gray-300 dark:bg-gray-600 mx-auto"></div>

                    <a class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('transacoes.*') ? $activeBg : ''); ?>"
                       :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                       href="<?php echo e(route('transacoes.index')); ?>"
                       :title="sidebarCollapsed ? 'Lançamentos' : ''">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M9 8L16 8M9 12L16 12M9 16L13 16M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Lançamentos</span>
                    </a>

                    <a class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('categorias.*') ? $activeBg : ''); ?>"
                       :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                       href="<?php echo e(route('categorias.index')); ?>"
                       :title="sidebarCollapsed ? 'Categorias' : ''">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 3H17C18.1046 3 19 3.89543 19 5V10H5V5C5 3.89543 5.89543 3 7 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 10V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V10H5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 14V17M10 14V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Categorias</span>
                    </a>

                    <a class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('bancos.*') ? $activeBg : ''); ?>"
                       :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                       href="<?php echo e(route('bancos.index')); ?>"
                       :title="sidebarCollapsed ? 'Bancos' : ''">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 6L12 3L21 6M4 8V18C4 18.5523 4.44772 19 5 19H19C19.5523 19 20 18.5523 20 18V8M5 10H19M6 12H7M9 12H10M12 12H13M15 12H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Bancos</span>
                    </a>

                    <a class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('cartoes.*') ? $activeBg : ''); ?>"
                       :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                       href="<?php echo e(route('cartoes.index')); ?>"
                       :title="sidebarCollapsed ? 'Cartões' : ''">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 8V18C3 19.1046 3.89543 20 5 20H19C20.1046 20 21 19.1046 21 18V8M3 8L6.5 10.5M21 8L17.5 10.5M3 8H21M17.5 10.5L14 12.5M6.5 10.5L10 12.5M12 14.5L10 12.5M12 14.5L14 12.5M12 14.5V17.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Cartões</span>
                    </a>
                </div>
            <?php endif; ?>
        </nav>

        
        <div class="space-y-1 border-t border-gray-200 dark:border-gray-700 pt-4">
            <label x-show="!sidebarCollapsed || isMobile" class="px-3 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Conta</label>
            <div x-show="sidebarCollapsed && !isMobile" class="w-full h-px bg-gray-300 dark:bg-gray-600 mx-auto"></div>

            <a
                class="<?php echo e($linkBase); ?> <?php echo e(request()->routeIs('profile.*') ? $activeBg : ''); ?>"
                :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                href="<?php echo e(route('profile.edit')); ?>"
                :title="sidebarCollapsed ? 'Perfil' : ''"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Perfil</span>
            </a>

            <button
                type="button"
                x-on:click="darkMode = !darkMode"
                class="<?php echo e($linkBase); ?>"
                :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                :title="sidebarCollapsed ? 'Alternar tema' : ''"
            >
                <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 008.25-4.5z" />
                </svg>
                <svg x-show="darkMode" style="display:none" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.909-2.636l1.591-1.591M5.25 12H3m4.243-4.909l-1.591-1.591M12 9a3 3 0 100 6 3 3 0 000-6z" />
                </svg>
                <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Alternar tema</span>
            </button>

            <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                <?php echo csrf_field(); ?>
                <button
                    type="submit"
                    class="flex items-center w-full rounded-lg text-red-600 transition-colors duration-300 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-red-500"
                    :class="(sidebarCollapsed && !isMobile) ? 'px-2 py-3 justify-center' : 'px-3 py-2'"
                    :title="sidebarCollapsed ? 'Sair' : ''"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || isMobile" class="mx-2 text-sm font-medium">Sair</span>
                </button>
            </form>
        </div>
    </div>
</aside>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>