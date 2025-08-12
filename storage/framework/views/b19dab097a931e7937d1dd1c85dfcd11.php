<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? 'Portfólio'); ?> - <?php echo e(config('app.name', 'Laravel')); ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo e($description ?? 'Portfólio profissional de design e ilustração'); ?>">
    <meta name="keywords" content="design, ilustração, portfólio, criativo">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo e($title ?? 'Portfólio'); ?> - <?php echo e(config('app.name', 'Laravel')); ?>">
    <meta property="og:description" content="<?php echo e($description ?? 'Portfólio profissional de design e ilustração'); ?>">
    <meta property="og:image" content="<?php echo e($ogImage ?? asset('images/logo.svg')); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-b border-gray-100 z-50">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="<?php echo e(route('portfolio.public.index')); ?>" class="flex items-center">
                            <?php if(auth()->user()?->logo_path): ?>
                                <img src="<?php echo e(asset('storage/' . auth()->user()->logo_path)); ?>" alt="Logo" class="h-8 w-auto">
                            <?php else: ?>
                                <span class="text-xl font-bold text-gray-900"><?php echo e(config('app.name', 'Portfolio')); ?></span>
                            <?php endif; ?>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-8">
                            <a href="<?php echo e(route('portfolio.public.index')); ?>" 
                               class="<?php echo e(request()->routeIs('portfolio.public.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-blue-600'); ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Portfólio
                            </a>
                            <a href="<?php echo e(route('services.public.index')); ?>" 
                               class="<?php echo e(request()->routeIs('services.public.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-blue-600'); ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Serviços
                            </a>
                            <a href="<?php echo e(route('about.public.index')); ?>" 
                               class="<?php echo e(request()->routeIs('about.public.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-blue-600'); ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Sobre
                            </a>
                            <a href="<?php echo e(route('contact.public.index')); ?>" 
                               class="<?php echo e(request()->routeIs('contact.public.*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-blue-600'); ?> px-3 py-2 text-sm font-medium transition-colors duration-200">
                                Contato
                            </a>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="hidden md:block">
                        <a href="<?php echo e(route('contact.public.index')); ?>" 
                           class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                            Solicitar Orçamento
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" 
                                class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                                aria-controls="mobile-menu" 
                                aria-expanded="false">
                            <span class="sr-only">Abrir menu principal</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div class="mobile-menu hidden md:hidden">
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-100">
                        <a href="<?php echo e(route('portfolio.public.index')); ?>" 
                           class="<?php echo e(request()->routeIs('portfolio.public.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50'); ?> block px-3 py-2 rounded-md text-base font-medium">
                            Portfólio
                        </a>
                        <a href="<?php echo e(route('services.public.index')); ?>" 
                           class="<?php echo e(request()->routeIs('services.public.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50'); ?> block px-3 py-2 rounded-md text-base font-medium">
                            Serviços
                        </a>
                        <a href="<?php echo e(route('about.public.index')); ?>" 
                           class="<?php echo e(request()->routeIs('about.public.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50'); ?> block px-3 py-2 rounded-md text-base font-medium">
                            Sobre
                        </a>
                        <a href="<?php echo e(route('contact.public.index')); ?>" 
                           class="<?php echo e(request()->routeIs('contact.public.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50'); ?> block px-3 py-2 rounded-md text-base font-medium">
                            Contato
                        </a>
                        <a href="<?php echo e(route('contact.public.index')); ?>" 
                           class="bg-blue-600 text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-700 mt-4">
                            Solicitar Orçamento
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="flex-1 pt-16">
            <?php if(isset($slot)): ?>
                <?php echo e($slot); ?>

            <?php else: ?>
                <?php echo $__env->yieldContent('content'); ?>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Logo e Descrição -->
                    <div class="md:col-span-2">
                        <div class="flex items-center mb-4">
                            <?php if(auth()->user()?->logo_path): ?>
                                <img src="<?php echo e(asset('storage/' . auth()->user()->logo_path)); ?>" alt="Logo" class="h-8 w-auto">
                            <?php else: ?>
                                <span class="text-xl font-bold"><?php echo e(config('app.name', 'Portfolio')); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-gray-400 mb-4">
                            Criando experiências visuais únicas através de design e ilustração profissional.
                        </p>
                        <?php if(auth()->user()?->whatsapp): ?>
                            <a href="https://wa.me/<?php echo e(auth()->user()->whatsapp); ?>" 
                               class="inline-flex items-center text-green-400 hover:text-green-300 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                WhatsApp
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Links Rápidos -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                        <ul class="space-y-2">
                            <li><a href="<?php echo e(route('portfolio.public.index')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200">Portfólio</a></li>
                            <li><a href="<?php echo e(route('services.public.index')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200">Serviços</a></li>
                            <li><a href="<?php echo e(route('about.public.index')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200">Sobre</a></li>
                            <li><a href="<?php echo e(route('contact.public.index')); ?>" class="text-gray-400 hover:text-white transition-colors duration-200">Contato</a></li>
                        </ul>
                    </div>

                    <!-- Contato -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contato</h3>
                        <ul class="space-y-2 text-gray-400">
                            <?php if(auth()->user()?->contact_email): ?>
                                <li><?php echo e(auth()->user()->contact_email); ?></li>
                            <?php endif; ?>
                            <?php if(auth()->user()?->whatsapp): ?>
                                <li><?php echo e(auth()->user()->whatsapp); ?></li>
                            <?php endif; ?>
                            <?php if(auth()->user()?->website_url): ?>
                                <li><a href="<?php echo e(auth()->user()->website_url); ?>" class="hover:text-white transition-colors duration-200">Website</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'Portfolio')); ?>. Todos os direitos reservados.</p>
                </div>
            </div>
        </footer>
    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    
    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\sistemaDM\resources\views/layouts/portfolio.blade.php ENDPATH**/ ?>