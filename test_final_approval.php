<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE FINAL DE APROVAÇÃO VIA LINK PÚBLICO ===\n\n";

try {
    // Buscar um orçamento em análise ou criar um para teste
    $budget = \App\Models\Orcamento::where('status', 'Analisando')->first();
    
    if (!$budget) {
        // Se não há orçamento em análise, vamos usar um aprovado e simular
        $budget = \App\Models\Orcamento::where('status', 'Aprovado')->first();
        if ($budget) {
            echo "Usando orçamento já aprovado para simulação: ID {$budget->id}\n";
        } else {
            echo "Nenhum orçamento encontrado para teste.\n";
            exit;
        }
    } else {
        echo "Orçamento em análise encontrado: ID {$budget->id}\n";
    }
    
    echo "Usuário responsável: {$budget->user->name} (ID: {$budget->user_id})\n";
    echo "Cliente: " . ($budget->cliente->nome ?? 'Cliente não informado') . "\n";
    echo "Valor: R$ " . number_format($budget->valor_total, 2, ',', '.') . "\n\n";
    
    // Contar notificações antes
    $notificationsBefore = \App\Models\Notification::where('user_id', $budget->user_id)
        ->where('type', 'budget_approved')
        ->count();
    echo "Notificações de aprovação antes: {$notificationsBefore}\n";
    
    // Simular aprovação via link público (como no PublicOrcamentoView)
    echo "\nSimulando aprovação via link público...\n";
    
    // Alterar status (se necessário)
    $originalStatus = $budget->status;
    $budget->status = 'Aprovado';
    $budget->save();
    
    // Disparar evento como no PublicOrcamentoView
    event(new \App\Events\BudgetApproved(
        $budget,
        $budget->user, // Usuário que criou o orçamento
        'Orçamento aprovado pelo cliente via link público',
        true // isPublicApproval = true
    ));
    
    echo "Evento BudgetApproved disparado com isPublicApproval=true\n";
    echo "Aguardando processamento da queue...\n\n";
    
    // Processar queue
    echo "Processando queue...\n";
    \Artisan::call('queue:work', ['--stop-when-empty' => true]);
    
    // Verificar notificações após
    $notificationsAfter = \App\Models\Notification::where('user_id', $budget->user_id)
        ->where('type', 'budget_approved')
        ->count();
    
    echo "\nNotificações de aprovação depois: {$notificationsAfter}\n";
    
    if ($notificationsAfter > $notificationsBefore) {
        echo "✅ SUCESSO! Nova notificação criada.\n";
        
        $latestNotification = \App\Models\Notification::where('user_id', $budget->user_id)
            ->where('type', 'budget_approved')
            ->latest()
            ->first();
            
        if ($latestNotification) {
            echo "\nDetalhes da notificação criada:\n";
            echo "  ID: {$latestNotification->id}\n";
            echo "  Usuário: {$latestNotification->user_id}\n";
            echo "  Título: {$latestNotification->title}\n";
            echo "  Mensagem: {$latestNotification->message}\n";
            echo "  Prioridade: {$latestNotification->priority}\n";
            echo "  Criada em: {$latestNotification->created_at}\n";
        }
    } else {
        echo "❌ FALHA! Nenhuma nova notificação foi criada.\n";
    }
    
    // Restaurar status original se necessário
    if ($originalStatus !== 'Aprovado') {
        $budget->status = $originalStatus;
        $budget->save();
        echo "\nStatus do orçamento restaurado para: {$originalStatus}\n";
    }
    
} catch (Exception $e) {
    echo "Erro: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
}

echo "\n=== FIM DO TESTE FINAL ===\n";