<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ADMIN - Gestão Inteligente para Freelancers</title>
        
        
        <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900">
        <div class="relative min-h-screen flex flex-col">
            
            
            <header class="w-full px-6 py-4">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    
                    <div class="flex items-center gap-2">
                         <img src="<?php echo e(asset('images/logo_admin.png')); ?>" alt="Logo ADMIN" class="h-8 w-auto">
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(url('/dashboard')); ?>" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Dashboard</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Entrar</a>
                            <?php if(Route::has('register')): ?>
                                <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Registar</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            
            <main class="flex-grow flex items-center">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        
                        <div class="text-center md:text-left">
                            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                                Organize seus projetos. Controle suas finanças.
                            </h1>
                            <p class="mt-6 text-lg text-gray-600 dark:text-gray-400">
                                O ADMIN é a ferramenta completa para freelancers que procuram simplicidade e poder na gestão do seu negócio.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                                <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md">
                                    Começar Agora
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                                <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                    Já tenho uma conta
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex justify-center">
                            <img src="<?php echo e(asset('images/boas_vindas.png')); ?>" alt="Ilustração de freelancers a trabalhar" class="w-full max-w-md">
                        </div>
                    </div>
                </div>
            </main>

            
            <footer class="w-full py-6 px-6 mt-16">
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    &copy; <?php echo e(date('Y')); ?> ADMIN. Todos os direitos reservados.
                </p>
            </footer>
        </div>
    </body>
</html>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/welcome.blade.php ENDPATH**/ ?>