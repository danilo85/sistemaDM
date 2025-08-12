<div>
    
    <section class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="<?php echo e(route('portfolio.public.index')); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Portfólio
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2"><?php echo e($work->category->name); ?></span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2"><?php echo e($work->title); ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-8">
                    <span class="inline-block px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-full mb-4">
                        <?php echo e($work->category->name); ?>

                    </span>
                    <h1 class="text-3xl lg:text-5xl font-bold text-gray-900 mb-4"><?php echo e($work->title); ?></h1>
                    <!--[if BLOCK]><![endif]--><?php if($work->description): ?>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto"><?php echo e($work->description); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <!--[if BLOCK]><![endif]--><?php if($work->client): ?>
                    <div class="text-center">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Cliente</h3>
                        <p class="text-lg text-gray-900"><?php echo e($work->client); ?></p>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <div class="text-center">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Categoria</h3>
                        <p class="text-lg text-gray-900"><?php echo e($work->category->name); ?></p>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Data</h3>
                        <p class="text-lg text-gray-900"><?php echo e($work->created_at->format('M Y')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <!--[if BLOCK]><![endif]--><?php if($work->images->count() > 0): ?>
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <?php if($work->images->count() === 1): ?>
                    
                    <div class="cursor-pointer" wire:click="openImageModal(0)">
                        <img src="<?php echo e(Storage::url($work->images->first()->image_path)); ?>" 
                             alt="<?php echo e($work->title); ?>"
                             class="w-full rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    </div>
                <?php else: ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $work->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="cursor-pointer group" wire:click="openImageModal(<?php echo e($index); ?>)">
                            <img src="<?php echo e(Storage::url($image->image_path)); ?>" 
                                 alt="<?php echo e($work->title); ?> - Imagem <?php echo e($index + 1); ?>"
                                 class="w-full rounded-lg shadow-md group-hover:shadow-lg transition-shadow aspect-[4/3] object-cover">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($work->content): ?>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-8 text-center">Sobre o Projeto</h2>
                <div class="prose prose-lg max-w-none text-gray-600">
                    <?php echo nl2br(e($work->content)); ?>

                </div>
            </div>
        </div>
    </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($relatedWorks->count() > 0): ?>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">Projetos Relacionados</h2>
                <p class="text-gray-600">Outros trabalhos da categoria <?php echo e($work->category->name); ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $relatedWorks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedWork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group cursor-pointer" wire:click="$redirect('<?php echo e(route('portfolio.public.show', $relatedWork->slug)); ?>')">
                    <div class="relative overflow-hidden rounded-lg bg-gray-100 aspect-[4/3] mb-4">
                        <!--[if BLOCK]><![endif]--><?php if($relatedWork->images->first()): ?>
                            <img src="<?php echo e(Storage::url($relatedWork->images->first()->image_path)); ?>" 
                                 alt="<?php echo e($relatedWork->title); ?>"
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
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors"><?php echo e($relatedWork->title); ?></h3>
                        <p class="text-gray-600"><?php echo e($relatedWork->category->name); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </section>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <section class="py-16 bg-blue-600">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl lg:text-3xl font-bold text-white mb-6">Gostou deste projeto?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Vamos conversar sobre como posso ajudar com seu próximo projeto.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('contact.public.index')); ?>" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Entrar em Contato
                </a>
                <a href="<?php echo e(route('portfolio.public.index')); ?>" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    Ver Mais Trabalhos
                </a>
            </div>
        </div>
    </section>

    
    <!--[if BLOCK]><![endif]--><?php if($showImageModal && $work->images->count() > 0): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90" 
         wire:click="closeImageModal"
         x-data="{ show: <?php if ((object) ('showImageModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showImageModal'->value()); ?>')<?php echo e('showImageModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showImageModal'); ?>')<?php endif; ?> }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        
        <button wire:click="closeImageModal" 
                class="absolute top-4 right-4 z-60 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        
        <?php if($work->images->count() > 1): ?>
            <button wire:click.stop="previousImage" 
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-60 text-white hover:text-gray-300 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button wire:click.stop="nextImage" 
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-60 text-white hover:text-gray-300 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <div class="max-w-7xl max-h-full mx-auto px-4" wire:click.stop>
            <img src="<?php echo e(Storage::url($work->images[$currentImageIndex]->image_path ?? $work->images->first()->image_path)); ?>" 
                 alt="<?php echo e($work->title); ?>"
                 class="max-w-full max-h-full object-contain rounded-lg">
        </div>

        
        <?php if($work->images->count() > 1): ?>
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-sm">
            <?php echo e($currentImageIndex + 1); ?> / <?php echo e($work->images->count()); ?>

        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php $__env->stopPush(); ?><?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/portfolio-work-detail.blade.php ENDPATH**/ ?>