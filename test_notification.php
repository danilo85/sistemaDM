<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE MANUAL DE NOTIFICAÇÃO ===\n\n";

// Buscar um orçamento aprovado recente
$budget = App\Models\Orcamento::where('status', 'Aprovado')->latest()->first();

if (!$budget) {
    echo "Nenhum orçamento aprovado encontrado.\n";
    exit;
}

echo "Orçamento encontrado: ID {$budget->id}, Status: {$budget->status}\n";
echo "User ID (responsável): {$budget->user_id}\n";
echo "Created by: {$budget->created_by}\n";

// Verificar se o usuário existe
$user = App\Models\User::find($budget->user_id);
if (!$user) {
    echo "Usuário não encontrado para o orçamento.\n";
    exit;
}

echo "Usuário responsável: {$user->name} (ID: {$user->id})\n";

// Usar o próprio usuário responsável como aprovador para simular aprovação via link público
$approver = $user;

// Verificar configurações de notificação
$settings = App\Models\NotificationSetting::getForUser($user->id);
echo "\nConfigurações de notificação do usuário responsável:\n";
echo "  Tipos habilitados: " . implode(', ', $settings->enabled_types) . "\n";
echo "  Budget approved habilitado: " . (in_array('budget_approved', $settings->enabled_types) ? 'SIM' : 'NÃO') . "\n";

// Criar evento manualmente
echo "\nCriando evento BudgetApproved manualmente...\n";
echo "  Orçamento: ID {$budget->id} (responsável: {$budget->user_id})\n";
echo "  Aprovado por: {$approver->name} (ID: {$approver->id})\n";

try {
    $event = new App\Events\BudgetApproved($budget, $approver, 'Teste manual de notificação', true);
    
    echo "\nEvento criado. Dados do evento:\n";
    $eventData = $event->getEventData();
    echo "  Título: {$eventData['notification_data']['title']}\n";
    echo "  Mensagem: {$eventData['notification_data']['message']}\n";
    echo "  Prioridade: {$eventData['notification_data']['priority']}\n";
    
    $usersToNotify = $event->getUsersToNotify();
    echo "  Usuários para notificar: " . implode(', ', $usersToNotify) . "\n";
    
    if (empty($usersToNotify)) {
        echo "  ⚠️ PROBLEMA: Nenhum usuário será notificado!\n";
        echo "  Motivo: O usuário que aprovou ({$approver->id}) é o mesmo que criou/é responsável pelo orçamento.\n";
    }
    
    // Disparar o evento
    echo "\nDisparando evento...\n";
    event($event);
    
    echo "Evento disparado com sucesso!\n";
    
    // Verificar se foi criada alguma notificação
    sleep(2); // Aguardar um pouco
    
    $notifications = App\Models\Notification::where('budget_id', $budget->id)
        ->where('type', 'budget_approved')
        ->get();
        
    echo "\nNotificações criadas: {$notifications->count()}\n";
    
    foreach ($notifications as $notification) {
        echo "  ID: {$notification->id}, User: {$notification->user_id}, Title: {$notification->title}\n";
    }
    
} catch (Exception $e) {
    echo "Erro ao criar/disparar evento: {$e->getMessage()}\n";
    echo "Stack trace: {$e->getTraceAsString()}\n";
}

echo "\n=== ANÁLISE DO PROBLEMA ===\n";
echo "O sistema de notificações está funcionando, mas há uma lógica que impede\n";
echo "notificações quando o usuário que aprova é o mesmo que criou o orçamento.\n";
echo "\nNo caso de aprovação via link público, o sistema deveria notificar o\n";
echo "usuário responsável pelo orçamento, mesmo que ele seja o 'aprovador'.\n";

echo "\n=== FIM DO TESTE ===\n";