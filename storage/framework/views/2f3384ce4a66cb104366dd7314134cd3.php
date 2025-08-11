<div>
     <?php $__env->slot('header', null, []); ?> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e($portfolio->exists ? 'Editar Trabalho' : 'Adicionar Trabalho ao Portfólio'); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form wire:submit.prevent="save" class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-6">

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Título do Trabalho</label>
                    <input type="text" wire:model="title" id="title" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div>
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoria</label>
                    <select wire:model="portfolio_category_id" id="category" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900">
                        <option value="">Selecione...</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['portfolio_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            
            <div>
                <label for="post_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data de Postagem</label>
                <?php if (isset($component)) { $__componentOriginal37e12294b28f0bd91a733acab9bb06c5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37e12294b28f0bd91a733acab9bb06c5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.date-picker','data' => ['name' => 'post_date','wire:model' => 'post_date','id' => 'post_date','value' => ''.e($post_date).'','class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('date-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'post_date','wire:model' => 'post_date','id' => 'post_date','value' => ''.e($post_date).'','class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37e12294b28f0bd91a733acab9bb06c5)): ?>
<?php $attributes = $__attributesOriginal37e12294b28f0bd91a733acab9bb06c5; ?>
<?php unset($__attributesOriginal37e12294b28f0bd91a733acab9bb06c5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37e12294b28f0bd91a733acab9bb06c5)): ?>
<?php $component = $__componentOriginal37e12294b28f0bd91a733acab9bb06c5; ?>
<?php unset($__componentOriginal37e12294b28f0bd91a733acab9bb06c5); ?>
<?php endif; ?>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['post_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
                <textarea wire:model="description" id="description" rows="8" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900"></textarea>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="external_link" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Link Externo (Opcional)</label>
                    <input type="url" wire:model="external_link" id="external_link" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900" placeholder="https://exemplo.com">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['external_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div>
                    <label for="link_legend" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Legenda do Link (Opcional)</label>
                    <input type="text" wire:model="link_legend" id="link_legend" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900" placeholder="Ver projeto">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['link_legend'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div x-data="{ thumbPreview: '<?php echo e($portfolio->thumb ? asset('storage/' . $portfolio->thumb->path) : ''); ?>', dragging: false, handleFileSelect(event) { if (event.target.files[0]) { this.thumbPreview = URL.createObjectURL(event.target.files[0]); } }, handleDrop(event) { if (event.dataTransfer.files[0]) { document.getElementById('thumb').files = event.dataTransfer.files; this.thumbPreview = URL.createObjectURL(event.dataTransfer.files[0]); } } }">
                    <label for="thumb" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Imagem de Capa (Thumbnail)</label>
                    <input id="thumb" wire:model="thumb" type="file" class="hidden" @change="handleFileSelect($event)" accept="image/png, image/jpeg, image/jpg">
                    <div @click="document.getElementById('thumb').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleDrop($event)" class="mt-1 cursor-pointer flex flex-col justify-center items-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-6 transition-colors h-32" :class="{'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': dragging}">
                        <template x-if="thumbPreview"><img :src="thumbPreview" alt="Prévia da Thumbnail" class="max-h-24 rounded-lg object-cover"></template>
                        <template x-if="!thumbPreview">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Clique</span> ou arraste uma imagem</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG até 10MB</p>
                            </div>
                        </template>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['thumb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <div x-data="{ dragging: false, previews: [], handleFileSelect(event) { this.updatePreviews(event.target.files); }, handleDrop(event) { if (event.dataTransfer.files.length > 0) { document.getElementById('images').files = event.dataTransfer.files; this.updatePreviews(event.dataTransfer.files); } }, updatePreviews(files) { this.previews = []; for (let i = 0; i < files.length; i++) { this.previews.push(URL.createObjectURL(files[i])); } } }">
                    <label for="images" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Outras Imagens (Galeria)</label>
                    <input id="images" wire:model="images" type="file" multiple class="hidden" @change="handleFileSelect($event)" accept="image/png, image/jpeg, image/jpg">
                    <div @click="document.getElementById('images').click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleDrop($event)" class="mt-1 cursor-pointer flex flex-col justify-center items-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 px-6 py-6 transition-colors h-32" :class="{'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': dragging}">
                        <div class="text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Clique</span> ou arraste múltiplas imagens</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG até 10MB cada</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-4">
                        
                        <template x-for="(preview, index) in previews" :key="index">
                            <img :src="preview" class="rounded-lg h-20 w-full object-cover">
                        </template>
                        
                        <!--[if BLOCK]><![endif]--><?php if($existingImages && $existingImages->count() > 0): ?>
                            <div id="existing-images-sortable" class="grid grid-cols-3 gap-4 col-span-3">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $existingImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="relative group cursor-move" data-image-id="<?php echo e($image->id); ?>">
                                        <img src="<?php echo e(asset('storage/' . $image->path)); ?>" 
                                             class="rounded-lg h-20 w-full object-cover cursor-pointer" 
                                             @click="$dispatch('open-image-preview', { src: '<?php echo e(asset('storage/' . $image->path)); ?>' })">
                                        
                                        
                                        <button type="button" 
                                                wire:click="deleteImage(<?php echo e($image->id); ?>)" 
                                                wire:confirm="Tem certeza que deseja remover esta imagem?"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600 z-10"
                                                title="Remover imagem">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        
                                        
                                        <div class="absolute top-1 left-1 bg-gray-800 bg-opacity-75 text-white rounded px-1 py-0.5 text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 15h18v-2H3v2zm0 4h18v-2H3v2zm0-8h18V9H3v2zm0-6v2h18V5H3z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['images.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
                <a href="<?php echo e($returnUrl); ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Cancelar
                </a>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    <?php echo e($portfolio->exists ? 'Atualizar Trabalho' : 'Salvar Trabalho'); ?>

                </button>
            </div>

        </form>
        </div>
    </div>

    
    <div x-data="{ 
        showPreview: false, 
        previewSrc: '',
        openPreview(src) {
            this.previewSrc = src;
            this.showPreview = true;
            document.body.style.overflow = 'hidden';
        },
        closePreview() {
            this.showPreview = false;
            document.body.style.overflow = 'auto';
        }
    }"
    @open-image-preview.window="openPreview($event.detail.src)"
    @keydown.escape.window="closePreview()"
    x-show="showPreview"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
    style="display: none;"
    @click="closePreview()">
        <div class="relative max-w-4xl max-h-full p-4" @click.stop>
            <img :src="previewSrc" class="max-w-full max-h-full object-contain rounded-lg">
            <button @click="closePreview()" 
                    class="absolute top-2 right-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-full p-2 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initSortable();
        });

        document.addEventListener('livewire:navigated', function() {
            initSortable();
        });

        function initSortable() {
            const existingImagesContainer = document.getElementById('existing-images-sortable');
            if (existingImagesContainer && !existingImagesContainer.sortableInstance) {
                existingImagesContainer.sortableInstance = Sortable.create(existingImagesContainer, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    chosenClass: 'ring-2 ring-blue-500',
                    dragClass: 'rotate-3 scale-105',
                    onEnd: function(evt) {
                        const imageIds = Array.from(existingImagesContainer.children).map(el => {
                            return el.dataset.imageId;
                        });
                        window.Livewire.find('<?php echo e($_instance->getId()); ?>').updateImageOrder(imageIds);
                    }
                });
            }
        }
    </script>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/portfolio/portfolio-form.blade.php ENDPATH**/ ?>