<?php

// REGRA 1: TODAS AS DECLARAÇÕES 'use' FICAM AQUI NO TOPO
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\CartaoCreditoController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\Admin\UserController;
use App\Livewire\PublicOrcamentoView;
use App\Livewire\Portfolio\CategoryManager;
use App\Livewire\Portfolio\Pipeline;
use App\Livewire\Portfolio\PortfolioForm;
use App\Livewire\Portfolio\PortfolioList;
use App\Http\Controllers\NotificationController;
use App\Livewire\KanbanBoard;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota pública principal
Route::get('/', function () {
    return view('welcome');
});

// Rotas públicas para orçamentos e recibos
Route::get('/orcamento/{orcamento:token}', PublicOrcamentoView::class)->name('orcamentos.public.show');
Route::get('/orcamentos/pdf/{token}/{parcelas?}', [OrcamentoController::class, 'gerarPDF'])->name('orcamentos.pdf');
Route::get('/recibos/{token}', [OrcamentoController::class, 'showPublicReceipt'])->name('receipts.public.show');
Route::get('/extrato/{cliente:token}', App\Livewire\PublicExtratoView::class)->name('extrato.public.show');

// Rotas públicas do portfólio
Route::get('/portfolio', App\Livewire\PublicPortfolioIndex::class)->name('portfolio.public.index');
Route::get('/portfolio/{slug}', App\Livewire\PortfolioWorkDetail::class)->name('portfolio.public.show');
Route::get('/portfolio/categoria/{categoria}', App\Livewire\PublicPortfolioIndex::class)->name('portfolio.public.category');
Route::get('/servicos', function () {
    return view('portfolio.services');
})->name('services.public.index');
Route::get('/sobre', function () {
    return view('portfolio.about');
})->name('about.public.index');
Route::get('/contato', function () {
    return view('portfolio.contact');
})->name('contact.public.index');
Route::post('/api/contato', [App\Http\Controllers\PortfolioContactController::class, 'store'])->name('api.contact.store');

// Grupo de rotas que exigem que o usuário esteja autenticado
Route::middleware(['auth', 'verified'])->group(function () {

    // Rotas de usuário comum - Dashboard simples
    Route::get('/dashboard', [App\Http\Controllers\DashboardSimplesController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');// Adicione esta rota para a ação de desfazer
    Route::patch('/orcamento/{orcamento:token}/revert', [App\Http\Controllers\OrcamentoController::class, 'revertStatus'])->name('orcamentos.public.revert_status');

    // CORREÇÃO: Grupo de rotas de administração com permissões granulares
    Route::prefix('admin')->group(function () {
        
        Route::middleware('can:gerenciar_usuarios')->group(function () {
            Route::resource('users', UserController::class)->names('admin.users');
        });

        Route::middleware('can:acessar_clientes')->group(function () {
            Route::resource('clientes', ClienteController::class)->names('clientes');
            Route::get('clientes/{cliente}/extrato-pdf', [ClienteController::class, 'gerarExtratoPdf'])->name('clientes.extrato.pdf');
        });
        
        Route::middleware('can:acessar_autores')->group(function () {
            Route::resource('autores', AutorController::class)->parameters(['autores' => 'autor'])->names('autores');
        });
        
        Route::middleware('can:acessar_orcamentos')->group(function () {
            Route::resource('orcamentos', OrcamentoController::class)->names('orcamentos');
        });
        Route::middleware('can:acessar_clientes')->group(function () { // Usando uma permissão existente por agora
            Route::get('/portfolio/categorias', CategoryManager::class)->name('portfolio.categories.index');
            Route::get('/portfolio/pipeline', Pipeline::class)->name('portfolio.pipeline.index');
            Route::get('/portfolio/trabalhos/novo/{orcamento_id?}', PortfolioForm::class)->name('portfolio.create');
            Route::get('/portfolio/trabalhos/{portfolio}/editar', PortfolioForm::class)->name('portfolio.edit');
            Route::get('/portfolio/trabalhos', PortfolioList::class)->name('portfolio.index');
            
            // Rota do Kanban de Trabalhos
            Route::get('/kanban', KanbanBoard::class)->name('kanban.index');
        });
        

        // Grupo para todas as rotas financeiras
        Route::middleware('can:acessar_financeiro')->group(function () {
            Route::resource('categorias', CategoriaController::class)->names('categorias');
            Route::resource('bancos', BancoController::class)->names('bancos');
            Route::resource('cartoes', CartaoCreditoController::class)->parameters(['cartoes' => 'cartao'])->names('cartoes');
            Route::get('cartoes/{cartao}/extrato', [CartaoCreditoController::class, 'extrato'])->name('cartoes.extrato');
            Route::resource('transacoes', TransacaoController::class)->parameters(['transacoes' => 'transacao'])->names('transacoes');
            Route::resource('pagamentos', PagamentoController::class)->names('pagamentos');
        });

        // Rotas de busca (podem ser acedidas por quem tem acesso a clientes ou autores)
        Route::middleware('can:acessar_clientes|acessar_autores')->group(function () {
            Route::get('/search/clientes', [ClienteController::class, 'search'])->name('search.clientes');
            Route::get('/search/autores', [AutorController::class, 'search'])->name('search.autores');
        });

        // Rota do Dashboard Simples (admin)
        Route::get('/dashboard-simples', [App\Http\Controllers\DashboardSimplesController::class, 'index'])->name('admin.dashboard.simples');
        
        // Rotas da API de Notificações
        Route::prefix('api/notifications')->name('api.notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
            Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::patch('/{id}/snooze', [NotificationController::class, 'snooze'])->name('snooze');
            Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::get('/settings', [NotificationController::class, 'getSettings'])->name('settings.get');
            Route::patch('/settings', [NotificationController::class, 'updateSettings'])->name('settings.update');
            Route::get('/stats', [NotificationController::class, 'getStats'])->name('stats');
            Route::get('/{id}/actions', [NotificationController::class, 'getActions'])->name('actions');
        });
        
        // Remover APIs do Dashboard Personalizável - não são mais necessárias
    });
});


// REGRA 2: A linha que carrega as rotas de autenticação (login, etc.) é a ÚLTIMA.
require __DIR__.'/auth.php';
