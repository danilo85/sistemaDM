<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE DO LISTENER VIA QUEUE ===\n\n";

try {
    // Buscar um orçamento aprovado
    $budget = \App\Models\Orcamento::where('status', 'Aprovado')->first();
    if (!$budget) {
        echo "Nenhum orçamento aprovado encontrado.\n";
        exit;
    }
    
    echo "Orçamento encontrado: ID {$budget->id}\n";
    echo "Usuário responsável: {$budget->user_id}\n";
    
    // Contar notificações antes
    $notificationsBefore = \App\Models\Notification::count();
    echo "Notificações antes: {$notificationsBefore}\n";
    
    // Criar e disparar evento
    $event = new \App\Events\BudgetApproved(
        $budget,
        $budget->user,
        'Teste via queue - ' . now()->format('H:i:s'),
        true // isPublicApproval
    );
    
    echo "\nUsuários para notificar: " . implode(', ', $event->getUsersToNotify()) . "\n";
    
    echo "\nDisparando evento via queue...\n";
    event($event);
    
    echo "Evento disparado! Aguarde o processamento da queue.\n";
    echo "Execute 'php artisan queue:work --stop-when-empty' para processar.\n";
    
    // Verificar jobs pendentes
    $pendingJobs = \DB::table('jobs')->count();
    echo "Jobs pendentes na queue: {$pendingJobs}\n";
    
} catch (Exception $e) {
    echo "Erro: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
}

echo "\n=== FIM DO TESTE ===\n";