<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE DE DEBUG DE NOTIFICAÇÕES ===\n\n";

try {
    // Buscar um orçamento existente
    $budget = \App\Models\Orcamento::where('status', 'Analisando')->first();
    
    if (!$budget) {
        echo "Nenhum orçamento com status 'Analisando' encontrado.\n";
        exit(1);
    }
    
    echo "Orçamento encontrado: #{$budget->id}\n";
    echo "Status atual: {$budget->status}\n";
    echo "User ID: {$budget->user_id}\n";
    echo "Created by: {$budget->created_by}\n\n";
    
    // Verificar configurações de notificação do usuário
    $settings = \App\Models\NotificationSetting::getForUser($budget->user_id);
    echo "Configurações do usuário {$budget->user_id}:\n";
    echo "Tipos habilitados: " . json_encode($settings->enabled_types) . "\n";
    echo "Budget approved habilitado: " . (in_array('budget_approved', $settings->enabled_types) ? 'SIM' : 'NÃO') . "\n\n";
    
    // Contar notificações antes
    $notificationsBefore = \App\Models\Notification::where('user_id', $budget->user_id)
        ->where('budget_id', $budget->id)
        ->where('type', 'budget_approved')
        ->count();
    echo "Notificações existentes para este orçamento: {$notificationsBefore}\n\n";
    
    // Simular aprovação
    echo "Simulando aprovação via link público...\n";
    
    $budget->status = 'Aprovado';
    $budget->save();
    
    // Disparar evento
    $event = new \App\Events\BudgetApproved(
        $budget,
        $budget->user,
        'Orçamento aprovado pelo cliente via link público - TESTE',
        true // isPublicApproval = true
    );
    
    echo "Usuários que devem ser notificados: " . json_encode($event->getUsersToNotify()) . "\n";
    
    event($event);
    echo "Evento BudgetApproved disparado\n";
    
    // Aguardar um pouco
    echo "Aguardando 10 segundos para processamento...\n";
    sleep(10);
    
    // Verificar se notificação foi criada
    $notificationsAfter = \App\Models\Notification::where('user_id', $budget->user_id)
        ->where('budget_id', $budget->id)
        ->where('type', 'budget_approved')
        ->count();
    
    echo "\nNotificações após o evento: {$notificationsAfter}\n";
    
    if ($notificationsAfter > $notificationsBefore) {
        echo "✅ SUCESSO: Notificação foi criada!\n";
        
        // Mostrar detalhes da notificação
        $notification = \App\Models\Notification::where('user_id', $budget->user_id)
            ->where('budget_id', $budget->id)
            ->where('type', 'budget_approved')
            ->latest()
            ->first();
            
        echo "Detalhes da notificação:\n";
        echo "ID: {$notification->id}\n";
        echo "Título: {$notification->title}\n";
        echo "Mensagem: {$notification->message}\n";
        echo "Prioridade: {$notification->priority}\n";
        echo "Criada em: {$notification->created_at}\n";
    } else {
        echo "❌ ERRO: Notificação não foi criada\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}