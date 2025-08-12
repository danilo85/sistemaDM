<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE DIRETO DO LISTENER ===\n\n";

try {
    // Buscar um orçamento aprovado
    $budget = \App\Models\Orcamento::where('status', 'Aprovado')->first();
    if (!$budget) {
        echo "Nenhum orçamento aprovado encontrado.\n";
        exit;
    }
    
    echo "Orçamento encontrado: ID {$budget->id}\n";
    echo "Usuário responsável: {$budget->user_id}\n";
    
    // Criar evento
    $event = new \App\Events\BudgetApproved(
        $budget,
        $budget->user,
        'Teste direto do listener',
        true // isPublicApproval
    );
    
    echo "\nUsuários para notificar: " . implode(', ', $event->getUsersToNotify()) . "\n";
    
    // Executar listener diretamente
    $listener = new \App\Listeners\SendBudgetApprovedNotification();
    
    echo "\nExecutando listener diretamente...\n";
    $listener->handle($event);
    
    // Verificar notificações criadas
    $notifications = \App\Models\Notification::where('budget_id', $budget->id)
        ->where('type', 'budget_approved')
        ->get();
    
    echo "\nNotificações criadas: {$notifications->count()}\n";
    
    foreach ($notifications as $notification) {
        echo "  - ID: {$notification->id}, User: {$notification->user_id}, Título: {$notification->title}\n";
    }
    
} catch (Exception $e) {
    echo "Erro: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
}

echo "\n=== FIM DO TESTE ===\n";