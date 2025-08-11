<div x-data="{ mounted: false }" x-init="setTimeout(() => { mounted = true; $wire.finishLoading(); }, 1500)">
     <?php $__env->slot('header', null, []); ?> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ml-5">
                        Meus Trabalhos
                    </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        
                        <div class="flex items-center gap-2">
                            
                            <div class="hidden md:flex flex-wrap items-center gap-2">
                                <button wire:click="$set('filtroCategoria', '')" 
                                        class="px-3 py-1 text-sm font-semibold rounded-full transition-colors <?php echo e($filtroCategoria === '' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600'); ?>">
                                    Todas as Categorias
                                </button>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button wire:click="$set('filtroCategoria', '<?php echo e($category->id); ?>')" 
                                            class="px-3 py-1 text-sm font-semibold rounded-full transition-colors <?php echo e($filtroCategoria == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600'); ?>">
                                        <?php echo e($category->name); ?>

                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            
                            
                            <div class="md:hidden relative" x-data="{ filterOpen: false }">
                                
                                <button @click="filterOpen = !filterOpen" 
                                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                                    </svg>
                                    <span>Filtros</span>
                                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': filterOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                
                                <div x-show="filterOpen" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.away="filterOpen = false"
                                     class="absolute left-0 mt-2 w-64 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
                                    <div class="py-2">
                                        
                                        <div class="border-b border-gray-200 dark:border-gray-600 pb-2 mb-2">
                                            <p class="px-4 py-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</p>
                                            <button wire:click="$set('filtroStatus', '')" 
                                                    @click="filterOpen = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroStatus === '' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                Todos
                                            </button>
                                            <button wire:click="$set('filtroStatus', '1')" 
                                                    @click="filterOpen = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroStatus === '1' ? 'bg-green-50 text-green-600 dark:bg-green-900 dark:text-green-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                Ativados
                                            </button>
                                            <button wire:click="$set('filtroStatus', '0')" 
                                                    @click="filterOpen = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroStatus === '0' ? 'bg-red-50 text-red-600 dark:bg-red-900 dark:text-red-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                Desativados
                                            </button>
                                        </div>
                                        
                                        
                                        <div>
                                            <p class="px-4 py-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Categorias</p>
                                            <button wire:click="$set('filtroCategoria', '')" 
                                                    @click="filterOpen = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroCategoria === '' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                Todas as Categorias
                                            </button>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button wire:click="$set('filtroCategoria', '<?php echo e($category->id); ?>')" 
                                                        @click="filterOpen = false"
                                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroCategoria == $category->id ? 'bg-blue-50 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                    <?php echo e($category->name); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="flex items-center justify-end gap-2" x-data="{ statusFilterOpen: false }">
                            
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('portfolio.portfolio-search');

$__html = app('livewire')->mount($__name, $__params, 'lw-2112950549-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            
                            <div class="flex items-center gap-2">
                                
                <div class="relative hidden md:block">
                    
                    <button @click="statusFilterOpen = !statusFilterOpen" 
                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            type="button">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                                        </svg>
                                    </button>
                                    
                                    
                                    <div x-show="statusFilterOpen" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         @click.away="statusFilterOpen = false"
                                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
                                        <div class="py-2">
                                            <p class="px-4 py-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</p>
                                            <button wire:click="$set('filtroStatus', '')" 
                                                    @click="statusFilterOpen = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroStatus === '' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                Todos
                                            </button>
                                            <button wire:click="$set('filtroStatus', '1')" 
                                                    @click="statusFilterOpen = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroStatus === '1' ? 'bg-green-50 text-green-600 dark:bg-green-900 dark:text-green-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                Ativados
                                            </button>
                                            <button wire:click="$set('filtroStatus', '0')" 
                                                    @click="statusFilterOpen = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo e($filtroStatus === '0' ? 'bg-red-50 text-red-600 dark:bg-red-900 dark:text-red-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                                                Desativados
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="portfolio-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" 
                     x-data="{ 
                         initSortable() {
                             if (window.Sortable && !this.sortableInstance) {
                                 this.sortableInstance = Sortable.create(this.$el, {
                                     animation: 150,
                                     ghostClass: 'sortable-ghost',
                                     chosenClass: 'sortable-chosen',
                                     dragClass: 'sortable-drag',
                                     onEnd: (evt) => {
                                         const portfolioId = evt.item.dataset.portfolioId;
                                         const newPosition = evt.newIndex;
                                         $wire.updateOrder(portfolioId, newPosition);
                                     }
                                 });
                             }
                         }
                     }"
                     x-init="setTimeout(() => initSortable(), 100)">
                    <!--[if BLOCK]><![endif]--><?php if($isLoading): ?>
                        
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < $skeletonCount; $i++): ?>
                            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden animate-pulse">
                                
                                <div class="w-full h-48 bg-gray-300 dark:bg-gray-700"></div>
                                
                                
                                <div class="p-4">
                                    
                                    <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded mb-2"></div>
                                    
                                    <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-2/3"></div>
                                </div>
                                
                                
                                <div class="absolute top-2 right-2">
                                    <div class="w-11 h-6 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
                                </div>
                            </div>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $portfolios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $portfolio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div data-portfolio-id="<?php echo e($portfolio->id); ?>" class="portfolio-item relative group bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden <?php echo e(!$portfolio->is_active ? 'opacity-60' : ''); ?> cursor-move">
                            <img src="<?php echo e(asset('storage/' . optional($portfolio->thumb)->path)); ?>" alt="<?php echo e($portfolio->title); ?>" class="w-full h-48 object-cover <?php echo e(!$portfolio->is_active ? 'grayscale' : ''); ?>">

                            
                            <!--[if BLOCK]><![endif]--><?php if($portfolio->featured): ?>
                                <div class="absolute top-2 left-2 z-10">
                                    <svg class="w-5 h-5 text-yellow-500 fill-current drop-shadow-lg" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-900 dark:text-white <?php echo e(!$portfolio->is_active ? 'text-gray-500 dark:text-gray-400' : ''); ?>"><?php echo e($portfolio->title); ?></h3>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php if(!$portfolio->is_active): ?>
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                            Desativado
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                            Ativo
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($portfolio->category->name); ?></p>
                                <!--[if BLOCK]><![endif]--><?php if($portfolio->featured): ?>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            Destaque
                                        </span>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                
                                <button wire:click="toggleFeatured(<?php echo e($portfolio->id); ?>)" 
                                        class="p-3 <?php echo e($portfolio->featured ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-600 hover:bg-gray-700'); ?> text-white rounded-full transition-colors" 
                                        title="<?php echo e($portfolio->featured ? 'Remover destaque' : 'Marcar como destaque'); ?>">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </button>
                                
                                
                                <a href="<?php echo e(route('portfolio.edit', $portfolio)); ?>" class="p-3 bg-blue-600 text-white rounded-full hover:bg-blue-700" title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                </a>
                                
                                
                                <button wire:click="delete(<?php echo e($portfolio->id); ?>)" wire:confirm="Tem a certeza?" class="p-3 bg-red-600 text-white rounded-full hover:bg-red-700" title="Excluir">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            
                            <div class="absolute top-2 right-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:click="toggleActive(<?php echo e($portfolio->id); ?>)" <?php echo e($portfolio->is_active ? 'checked' : ''); ?> class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="sm:col-span-2 md:col-span-3 lg:col-span-4 text-center py-12">
                                <p class="text-gray-500 dark:text-gray-400">Nenhum trabalho encontrado.</p>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="mt-6">
                    <?php echo e($portfolios->links()); ?>

                </div>
            </div>
        </div>
    </div>

    
    <a href="<?php echo e(route('portfolio.create')); ?>" 
       class="fixed bottom-6 right-6 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center z-50"
       title="Adicionar Trabalho">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
    </a>
    
    
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #f3f4f6;
            transform: rotate(5deg);
        }
        
        .sortable-chosen {
            cursor: grabbing !important;
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        
        .sortable-drag {
            opacity: 0.8;
            transform: rotate(5deg);
        }
        
        .portfolio-item {
            transition: all 0.3s ease;
        }
        
        .portfolio-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .cursor-move {
            cursor: grab;
        }
        
        .cursor-move:active {
            cursor: grabbing;
        }
    </style>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/portfolio/portfolio-list.blade.php ENDPATH**/ ?>