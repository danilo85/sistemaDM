
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #<?php echo e($receipt->numero); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,400;6..12,700;6..12,900&display=swap" rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        body { font-family: 'Nunito Sans', sans-serif; }
        
        /* ESTILOS ESPECÍFICOS PARA IMPRESSÃO */
        @media print {
            .no-print { display: none !important; }
            
            /* Força o fundo branco e remove margens do corpo */
            body {
                background-color: #ffffff !important;
                margin: 0 !important;
            }

            /* Remove todos os estilos do container principal durante a impressão */
            .print-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 antialiased">
    <?php
        $user = $receipt->pagamento->orcamento->user;
    ?>
    <div class="container mx-auto max-w-3xl my-8 sm:my-12 px-4">
        
        <div class="bg-white p-8 md:p-12 shadow-lg rounded-lg print-container">

            
            <header class="flex justify-between items-start mb-12">
                <div>
                    <h1 class="text-5xl font-black text-gray-800 tracking-tighter">RECIBO</h1>
                    <div class="mt-4 text-sm text-gray-500">
                        <p>Número: <span class="font-semibold text-gray-700"><?php echo e($receipt->numero); ?></span></p>
                        <p>Data de Emissão: <span class="font-semibold text-gray-700"><?php echo e(\Carbon\Carbon::parse($receipt->data_emissao)->format('d/m/Y')); ?></span></p>
                    </div>
                </div>
                <div>
                    <?php if($user && $user->logo_path): ?>
                        <img src="<?php echo e(asset('storage/' . $user->logo_path)); ?>" alt="Logomarca" class="h-16">
                    <?php endif; ?>
                </div>
            </header>

            
            <main class="mb-12">
                <p class="text-lg leading-relaxed text-gray-700 text-justify">
                    Eu, <span class="font-bold"><?php echo e(optional($user)->name ?? 'Usuário Removido'); ?></span>,
                    <?php if($user && $user->cpf_cnpj): ?>
                        inscrito(a) no CPF/CNPJ sob o nº <span class="font-bold"><?php echo e($user->cpf_cnpj); ?></span>,
                    <?php endif; ?>
                    declaro para os devidos fins que recebi de <span class="font-bold"><?php echo e($receipt->pagamento->orcamento->cliente->name); ?></span>,
                    a importância de <span class="font-bold">R$ <?php echo e(number_format($receipt->valor, 2, ',', '.')); ?></span>
                    (<span class="italic"><?php echo e((new \NumberFormatter('pt-BR', \NumberFormatter::SPELLOUT))->format($receipt->valor)); ?></span>),
                    referente ao pagamento de <span class="font-bold"><?php echo e($receipt->pagamento->tipo_pagamento); ?></span>
                    do projeto <span class="font-bold">"<?php echo e($receipt->pagamento->orcamento->titulo); ?>"</span>.
                </p>
            </main>

            
            <div class="text-center mt-20">
                <?php if($user && $user->signature_path): ?>
                    <img src="<?php echo e(asset('storage/' . $user->signature_path)); ?>" alt="Assinatura" class="mx-auto h-20">
                <?php endif; ?>
                <div class="border-t border-gray-400 w-64 mx-auto mt-2 pt-2">
                    <p class="font-semibold text-gray-800"><?php echo e(optional($user)->name ?? 'Usuário Removido'); ?></p>
                </div>
            </div>

            
            <div class="no-print mt-16 text-center">
                <button onclick="window.print()" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    Imprimir / Salvar PDF
                </button>
            </div>
        </div>

        
        <footer class="mt-8 text-center text-sm text-gray-500 no-print">
            <p>Recibo gerado por sistemaDM</p>
        </footer>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/receipts/public-show.blade.php ENDPATH**/ ?>