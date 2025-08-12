<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE LIMPO DE APROVAÇÃO VIA LINK PÚBLICO ===\n\n";

try {
    // Buscar um orçamento que não tenha notificações recentes
    $budget = \App\Models\Orcamento::whereNotIn('id', function($query) {
        $query->select('budget_id')
              ->from('notifications')
              ->where('type', 'budget_approved')
              ->where('created_at', '>=', now()->subMinutes(10));
    })->where('status', 'Analisando')->first();
    
    if (!$budget) {
        echo "Nenhum orçamento sem notificações recentes encontrado.\n";
        echo "Criando um novo orçamento para teste...\n";
        
        // Criar um orçamento de teste
        $budget = new \App\Models\Orcamento();
        $budget->user_id = 1;
        $budget->titulo = 'Teste de Notificação - ' . now()->format('H:i:s');
        $budget->descricao = 'Orçamento criado para testar notificações';
        $budget->valor_total = 100.00;
        $budget->status = 'Analisando';
        $budget->token = \Str::random(32);
        $budget->save();
        
        echo "Orçamento criado: ID {$budget->id}\n";
    } else {
        echo "Orçamento sem notificações recentes encontrado: ID {$budget->id}\n";
    }
    
    echo "Usuário responsável: {$budget->user_id}\n";
    echo "Status atual: {$budget->status}\n\n";
    
    // Contar notificações antes
    $notificationsBefore = \App\Models\Notification::where('user_id', $budget->user_id)
        ->where('budget_id', $budget->id)
        ->where('type', 'budget_approved')
        ->count();
    echo "Notificações para este orçamento antes: {$notificationsBefore}\n";
    
    // Simular aprovação via link público
    echo "\nSimulando aprovação via link público...\n";
    
    $budget->status = 'Aprovado';
    $budget->save();
    
    // Disparar evento como no PublicOrcamentoView
    event(new \App\Events\BudgetApproved(
        $budget,
        $budget->user, // Usuário que criou o orçamento
        'Orçamento aprovado pelo cliente via link público - Teste ' . now()->format('H:i:s'),
        true // isPublicApproval = true
    ));
    
    echo "Evento BudgetApproved disparado com isPublicApproval=true\n";
    echo "Aguardando processamento da queue...\n\n";
    
    // Processar queue
    echo "Processando queue...\n";
    \Artisan::call('queue:work', ['--stop-when-empty' => true]);
    
    // Aguardar um pouco
    sleep(2);
    
    // Verificar notificações após
    $notificationsAfter = \App\Models\Notification::where('user_id', $budget->user_id)
        ->where('budget_id', $budget->id)
        ->where('type', 'budget_approved')
        ->count();
    
    echo "\nNotificações para este orçamento depois: {$notificationsAfter}\n";
    
    if ($notificationsAfter > $notificationsBefore) {
        echo "✅ SUCESSO! Nova notificação criada.\n";
        
        $latestNotification = \App\Models\Notification::where('user_id', $budget->user_id)
            ->where('budget_id', $budget->id)
            ->where('type', 'budget_approved')
            ->latest()
            ->first();
            
        if ($latestNotification) {
            echo "\nDetalhes da notificação criada:\n";
            echo "  ID: {$latestNotification->id}\n";
            echo "  Usuário: {$latestNotification->user_id}\n";
            echo "  Orçamento: {$latestNotification->budget_id}\n";
            echo "  Título: {$latestNotification->title}\n";
            echo "  Mensagem: {$latestNotification->message}\n";
            echo "  Prioridade: {$latestNotification->priority}\n";
            echo "  Criada em: {$latestNotification->created_at}\n";
        }
    } else {
        echo "❌ FALHA! Nenhuma nova notificação foi criada.\n";
        
        // Verificar logs para entender o problema
        echo "\nVerificando logs recentes...\n";
        $logs = file_get_contents(storage_path('logs/laravel.log'));
        $recentLogs = array_slice(explode("\n", $logs), -20);
        foreach ($recentLogs as $log) {
            if (strpos($log, 'SendBudgetApprovedNotification') !== false) {
                echo "  {$log}\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Erro: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
}

echo "\n=== FIM DO TESTE LIMPO ===\n";