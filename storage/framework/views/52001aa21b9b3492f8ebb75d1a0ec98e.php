<div>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        .font-open-sans {
            font-family: 'Open Sans', sans-serif;
        }
    </style>

    
    <style>
        @media print {
            body {
                background-color: #fff !important;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-bg-transparent {
                background-color: transparent !important;
            }
            .print-grid-cols-2 {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }
    </style>

    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
             class="no-print fixed top-5 right-5 z-50 rounded-md bg-green-600 px-4 py-3 text-sm font-bold text-white shadow-lg font-open-sans">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <?php if(session()->has('undo_action')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 8000)" x-show="show" x-transition
            class="no-print fixed bottom-5 right-5 z-50 rounded-md bg-gray-800 px-4 py-3 text-sm text-white shadow-lg font-open-sans">
            <div class="flex items-center gap-4">
                <p><?php echo e(session('undo_action')); ?></p>
                <button wire:click="revertStatus" class="font-bold underline hover:text-yellow-400">Desfazer</button>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 font-open-sans">
        <div class="bg-white p-8 md:p-12 shadow-lg rounded-md relative print-container">
            
            <?php
                $statusColors = ['analisando' => 'bg-yellow-400', 'aprovado' => 'bg-green-500', 'rejeitado' => 'bg-red-500', 'finalizado' => 'bg-blue-500', 'pago' => 'bg-purple-500', 'default' => 'bg-gray-400'];
                $statusClass = $statusColors[strtolower($orcamento->status)] ?? $statusColors['default'];
            ?>
            <div title="Status: <?php echo e($orcamento->status); ?>" class="no-print absolute top-8 right-8 z-10 flex h-6 w-6 items-center justify-center">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-opacity-75 <?php echo e($statusClass); ?>"></span>
                <span class="relative inline-flex h-4 w-4 rounded-full <?php echo e($statusClass); ?>"></span>
            </div>

            <header class="flex justify-between items-start mb-12">
                <div>
                    <h1 class="text-6xl font-black text-gray-800 tracking-tighter">PROPOSTA</h1>
                    <div class="mt-4 text-sm text-gray-500">
                        <p>Válido de <?php echo e($orcamento->data_emissao->format('d/m/Y')); ?> a <?php echo e($orcamento->data_validade->format('d/m/Y')); ?></p>
                        <p>Para <span class="font-semibold text-gray-700"><?php echo e($orcamento->cliente->name); ?></span></p>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-800 text-white w-20 h-20 flex items-center justify-center rounded-full">
                        <span class="text-2xl font-bold"><?php echo e($orcamento->numero); ?></span>
                    </div>
                </div>
            </header>

            <main>
                <div class="mb-10">
                    <h2 class="font-bold text-gray-800">Orçamento: <?php echo e($orcamento->titulo); ?></h2>
                    <div class="prose max-w-none text-gray-600 mt-2 text-justify">
                        <?php echo $orcamento->descricao; ?>

                    </div>
                </div>

                <div class="mb-10">
                    <p class="font-bold text-gray-800">Prazo: <span class="font-normal text-gray-600">Aproximadamente <?php echo e($orcamento->prazo_entrega_dias); ?> dias úteis.</span></p>
                </div>

                <div class="bg-gray-100 rounded-md p-8 mb-10 print-bg-transparent">
                    <div>
                        <p class="text-gray-500 uppercase text-sm">Total</p>
                        <p class="text-6xl font-extrabold text-gray-800">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></p>
                        <p class="mt-2 text-sm text-gray-500">Forma de pagamento: <?php echo e($orcamento->condicoes_pagamento); ?></p>
                    </div>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($parcelas == 2 && $orcamento->valor_total > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 text-white font-bold rounded-md overflow-hidden print-grid-cols-2">
                        <div class="bg-gray-800 p-8">
                            <p class="text-sm uppercase">40% para iniciar</p>
                            <p class="text-3xl font-bold">1º R$ <?php echo e(number_format($orcamento->valor_total * 0.4, 2, ',', '.')); ?></p>
                        </div>
                        <div class="bg-gray-700 p-8">
                            <p class="text-sm uppercase">60% ao término</p>
                            <p class="text-3xl font-bold">2º R$ <?php echo e(number_format($orcamento->valor_total * 0.6, 2, ',', '.')); ?></p>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <div class="bg-white p-6 mt-10" x-data="{ confirmingApproval: false, confirmingRejection: false }">
                     <!--[if BLOCK]><![endif]--><?php if(strtolower($orcamento->status) === 'analisando'): ?>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-800">Ações</h3>
                            <p class="text-gray-600 mt-1 mb-4">O que você gostaria de fazer em relação a esta proposta?</p>
                            <div class="flex justify-center flex-wrap gap-4 no-print">
                                <button @click="confirmingApproval = true" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Aprovar Proposta</button>
                                <button @click="confirmingRejection = true" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Rejeitar</button>
                                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Imprimir / Salvar PDF</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-lg font-semibold italic text-gray-600">Este orçamento foi <?php echo e(strtolower($orcamento->status)); ?> em <?php echo e($orcamento->updated_at->format('d/m/Y')); ?>.</p>
                             <div class="flex justify-center flex-wrap gap-4 mt-4 no-print">
                                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Imprimir / Salvar PDF</button>
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <div x-show="confirmingRejection" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 no-print" style="display: none;"><div @click.away="confirmingRejection = false" class="bg-white rounded-lg p-6 max-w-sm mx-auto text-center"><h3 class="text-lg font-bold">Confirmar Rejeição</h3><p class="mt-2 text-sm text-gray-600">Tem certeza que deseja rejeitar esta proposta?</p><div class="mt-4 flex justify-center gap-4"><button @click="confirmingRejection = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button><button wire:click="rejeitarOrcamento" @click="confirmingRejection = false" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Sim, Rejeitar</button></div></div></div>
                    <div x-show="confirmingApproval" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 no-print" style="display: none;"><div @click.away="confirmingApproval = false" class="bg-white rounded-lg p-6 max-w-sm mx-auto text-center"><h3 class="text-lg font-bold">Confirmar Aprovação</h3><p class="mt-2 text-sm text-gray-600">Tem certeza que deseja aprovar este orçamento?</p><div class="mt-4 flex justify-center gap-4"><button @click="confirmingApproval = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button><button wire:click="aprovarOrcamento" @click="confirmingApproval = false" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Sim, Aprovar</button></div></div></div>
                </div>
            </main>

            <footer class="mt-12 flex justify-between items-center no-print">
                <div class="flex items-center gap-6">
                    <div>
                        <!--[if BLOCK]><![endif]--><?php if(optional($orcamento->user)->logo_path): ?>
                            <img src="<?php echo e(asset('storage/' . $orcamento->user->logo_path)); ?>" alt="Logo" class="h-16">
                        <?php else: ?>
                            <p class="font-bold text-lg"><?php echo e($orcamento->user->name); ?></p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="text-sm text-gray-500 space-y-2">
                        <?php if(optional($orcamento->user)->website_url): ?>
                            <a href="<?php echo e($orcamento->user->website_url); ?>" target="_blank" class="flex items-center gap-2 hover:text-blue-500">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.665l3-3.001z"></path><path d="M4.468 12.232a2.5 2.5 0 010-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 005.656 5.656l3-3a4 4 0 00-.225-5.865.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.665l-3 3a2.5 2.5 0 01-3.536 0z"></path></svg>
                                <span><?php echo e($orcamento->user->website_url); ?></span>
                            </a>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php if(optional($orcamento->user)->behance_url): ?>
                            <a href="<?php echo e($orcamento->user->behance_url); ?>" target="_blank" class="flex items-center gap-2 hover:text-blue-500">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" /></svg>
                                <span><?php echo e($orcamento->user->behance_url); ?></span>
                            </a>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                         <?php if(optional($orcamento->user)->whatsapp): ?>
                            <a href="https://wa.me/<?php echo e(preg_replace('/\D/', '', $orcamento->user->whatsapp)); ?>" target="_blank" class="flex items-center gap-2 hover:text-blue-500">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.894 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.523.074-.797.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span><?php echo e($orcamento->user->whatsapp); ?></span>
                            </a>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <div class="bg-white p-2 rounded-md">
                    <?php
                        $publicUrl = route('orcamentos.public.show', ['orcamento' => $orcamento]);
                    ?>
                    <?php echo QrCode::size(100)->generate($publicUrl); ?>

                </div>
            </footer>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/public-orcamento-view.blade.php ENDPATH**/ ?>