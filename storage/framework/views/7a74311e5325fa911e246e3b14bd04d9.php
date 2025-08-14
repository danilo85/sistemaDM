<div>
    
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
                    Gestão de Clientes
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Gerencie seus clientes e informações de contato
                </p>
            </div>
            
            
            <div class="flex-shrink-0 w-full lg:w-80">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar clientes..."
                           class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:focus:ring-green-400 sm:text-sm">
                    
                    <!--[if BLOCK]><![endif]--><?php if($search): ?>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button wire:click="$set('search', '')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>

    
    <div class="mb-4 flex flex-wrap gap-2">
        <button wire:click="sortBy('name')" class="flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            Nome
            <!--[if BLOCK]><![endif]--><?php if($sortField === 'name'): ?>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="<?php echo e($sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7'); ?>" /></svg>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </button>
        <button wire:click="sortBy('email')" class="flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
            Email
            <!--[if BLOCK]><![endif]--><?php if($sortField === 'email'): ?>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="<?php echo e($sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7'); ?>" /></svg>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </button>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $cores = [
                    'white' => 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-600',
                    'blue' => 'bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-700',
                    'green' => 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-700',
                    'pink' => 'bg-pink-50 border-pink-200 dark:bg-pink-900/20 dark:border-pink-700',
                    'yellow' => 'bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-700',
                    'orange' => 'bg-orange-50 border-orange-200 dark:bg-orange-900/20 dark:border-orange-700'
                ];
                $corClasse = $cores[$cliente->cor] ?? $cores['white'];
            ?>
            <div wire:key="<?php echo e($cliente->id); ?>" class="<?php echo e($corClasse); ?> rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                
                <div class="p-6 pb-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            
                            <div class="flex-shrink-0">
                                <!--[if BLOCK]><![endif]--><?php if($cliente->logo): ?>
                                    <img src="<?php echo e(asset('storage/' . $cliente->logo)); ?>" alt="Logo <?php echo e($cliente->name); ?>" class="w-12 h-12 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center border-2 border-white dark:border-gray-600 shadow-sm">
                                        <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                <a href="<?php echo e(route('clientes.show', $cliente)); ?>" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <?php echo e($cliente->name); ?>

                                </a>
                            </h3>
                        </div>
                        <div class="flex items-center gap-2 ml-2">
                            <!--[if BLOCK]><![endif]--><?php if(!$cliente->is_complete): ?>
                                <span class="text-yellow-500" title="Cadastro Incompleto">⚠️</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 top-8 z-20 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 min-w-[120px]">
                                    <div class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-600">
                                        Cores
                                    </div>
                                    <div class="p-2">
                                        <div class="flex gap-1">
                                            <button wire:click="updateCor(<?php echo e($cliente->id); ?>, 'white')" @click="open = false" class="w-6 h-6 rounded-full bg-white border border-gray-300 hover:scale-110 transition-transform <?php echo e($cliente->cor === 'white' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : ''); ?>" title="Branco"></button>
                                            <button wire:click="updateCor(<?php echo e($cliente->id); ?>, 'blue')" @click="open = false" class="w-6 h-6 rounded-full bg-blue-500 hover:scale-110 transition-transform <?php echo e($cliente->cor === 'blue' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : ''); ?>" title="Azul"></button>
                                            <button wire:click="updateCor(<?php echo e($cliente->id); ?>, 'green')" @click="open = false" class="w-6 h-6 rounded-full bg-green-500 hover:scale-110 transition-transform <?php echo e($cliente->cor === 'green' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : ''); ?>" title="Verde"></button>
                                            <button wire:click="updateCor(<?php echo e($cliente->id); ?>, 'yellow')" @click="open = false" class="w-6 h-6 rounded-full bg-yellow-500 hover:scale-110 transition-transform <?php echo e($cliente->cor === 'yellow' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : ''); ?>" title="Amarelo"></button>
                                            <button wire:click="updateCor(<?php echo e($cliente->id); ?>, 'pink')" @click="open = false" class="w-6 h-6 rounded-full bg-pink-500 hover:scale-110 transition-transform <?php echo e($cliente->cor === 'pink' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : ''); ?>" title="Rosa"></button>
                                            <button wire:click="updateCor(<?php echo e($cliente->id); ?>, 'orange')" @click="open = false" class="w-6 h-6 rounded-full bg-orange-500 hover:scale-110 transition-transform <?php echo e($cliente->cor === 'orange' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : ''); ?>" title="Laranja"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <!--[if BLOCK]><![endif]--><?php if($cliente->email): ?>
                                <a href="mailto:<?php echo e($cliente->email); ?>" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline truncate">
                                    <?php echo e($cliente->email); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-gray-400 dark:text-gray-500">Não informado</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    
                    
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <!--[if BLOCK]><![endif]--><?php if($cliente->phone): ?>
                                <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $cliente->phone)); ?>" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:underline">
                                    <?php echo e($cliente->phone); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-gray-400 dark:text-gray-500">Não informado</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
                
                
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-center gap-4">
                        <a href="<?php echo e(route('clientes.show', $cliente->id)); ?>" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors" title="Cliente 360">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        
                        <a href="<?php echo e(route('clientes.edit', $cliente->id)); ?>" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors" title="Editar">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z" />
                            </svg>
                        </a>
                        
                        <form method="POST" action="<?php echo e(route('clientes.destroy', $cliente->id)); ?>" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Excluir">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum cliente encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando um novo cliente.</p>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <div class="mt-6">
        <?php echo e($clientes->links()); ?>

    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/cliente-list.blade.php ENDPATH**/ ?>