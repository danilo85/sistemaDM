<div>
    
    <section class="relative bg-gradient-to-br from-gray-50 to-white py-20 lg:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Criando experiências
                    <span class="text-blue-600">visuais únicas</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Designer especializado em identidade visual, ilustração digital e design de jogos. 
                    Transformo ideias em soluções criativas que conectam marcas aos seus públicos.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#portfolio" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Ver Portfólio
                    </a>
                    <a href="#contato" class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold hover:border-gray-400 transition-colors">
                        Entrar em Contato
                    </a>
                </div>
            </div>
        </div>
    </section>

    
    <!--[if BLOCK]><![endif]--><?php if($featuredWorks->count() > 0): ?>
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Trabalhos em Destaque</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Uma seleção dos meus projetos mais recentes e impactantes</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $featuredWorks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group cursor-pointer" wire:click="viewWork('<?php echo e($work->slug); ?>')">
                    <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/3] mb-4">
                        <!--[if BLOCK]><![endif]--><?php if($work->images->first()): ?>
                            <img src="<?php echo e(Storage::url($work->images->first()->image_path)); ?>" 
                                 alt="<?php echo e($work->title); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors"><?php echo e($work->title); ?></h3>
                        <p class="text-gray-600"><?php echo e($work->category->name); ?></p>
                        <!--[if BLOCK]><![endif]--><?php if($work->client): ?>
                            <p class="text-sm text-gray-500">Cliente: <?php echo e($work->client); ?></p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($services->count() > 0): ?>
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Serviços</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Soluções criativas personalizadas para cada necessidade</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <!--[if BLOCK]><![endif]--><?php if($service->icon): ?>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                            <i class="<?php echo e($service->icon); ?> text-blue-600 text-xl"></i>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><?php echo e($service->title); ?></h3>
                    <p class="text-gray-600 leading-relaxed"><?php echo e($service->description); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <section id="portfolio" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Portfólio Completo</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Explore todos os meus trabalhos e projetos</p>
            </div>

            
            <div class="mb-12">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    
                    <div class="relative w-full lg:w-96">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Buscar trabalhos..."
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="$set('selectedCategory', '')"
                                class="px-4 py-2 rounded-lg font-medium transition-colors <?php echo e($selectedCategory === '' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                            Todos
                        </button>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button wire:click="$set('selectedCategory', '<?php echo e($category->id); ?>')"
                                class="px-4 py-2 rounded-lg font-medium transition-colors <?php echo e($selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                            <?php echo e($category->name); ?>

                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($search || $selectedCategory): ?>
                <div class="mt-4 text-center">
                    <button wire:click="clearFilters" class="text-blue-600 hover:text-blue-700 font-medium">
                        Limpar filtros
                    </button>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <!--[if BLOCK]><![endif]--><?php if($works->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $works; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group cursor-pointer" wire:click="viewWork('<?php echo e($work->slug); ?>')">
                        <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/3] mb-4">
                        <!--[if BLOCK]><![endif]--><?php if($work->images->first()): ?>
                            <img src="<?php echo e(Storage::url($work->images->first()->image_path)); ?>" 
                                 alt="<?php echo e($work->title); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors"><?php echo e($work->title); ?></h3>
                        <p class="text-sm text-gray-600"><?php echo e($work->category->name); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div class="flex justify-center">
                <?php echo e($works->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.467-.881-6.08-2.33" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum trabalho encontrado</h3>
                <p class="text-gray-600">Tente ajustar os filtros ou termos de busca.</p>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </section>

    
    <!--[if BLOCK]><![endif]--><?php if($testimonials->count() > 0): ?>
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Depoimentos</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">O que meus clientes dizem sobre o trabalho</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-8 rounded-xl shadow-sm">
                    <div class="flex items-center mb-4">
                        <!--[if BLOCK]><![endif]--><?php for($i = 1; $i <= 5; $i++): ?>
                            <svg class="w-5 h-5 <?php echo e($i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"<?php echo e($testimonial->content); ?>"</p>
                    <div class="flex items-center">
                        <!--[if BLOCK]><![endif]--><?php if($testimonial->client_photo): ?>
                            <img src="<?php echo e($testimonial->client_photo_url); ?>" 
                                 alt="<?php echo e($testimonial->client_name); ?>"
                                 class="w-12 h-12 rounded-full object-cover mr-4">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <div>
                            <p class="font-semibold text-gray-900"><?php echo e($testimonial->client_name); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <section id="contato" class="py-20 bg-blue-600">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">Pronto para começar seu projeto?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Vamos conversar sobre como posso ajudar a transformar suas ideias em realidade.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('contact.public.index')); ?>" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Entrar em Contato
                </a>
                <a href="<?php echo e(route('about.public.index')); ?>" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    Sobre Mim
                </a>
            </div>
        </div>
    </section>
</div><?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/public-portfolio-index.blade.php ENDPATH**/ ?>