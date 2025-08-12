<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE FINAL DE APROVAÇÃO VIA LINK PÚBLICO ===\n\n";

try {
    // Buscar um orçamento para testar
    $budget = \App\Models\Orcamento::where('status', 'Aprovado')->first();
    
    if (!$budget) {
        echo "Nenhum orçamento encontrado. Criando um para teste...\n";
        // Aqui você poderia criar um orçamento de teste se necessário
        exit(1);
    }
    
    // Resetar status para testar
    $budget->status = 'Analisando';
    $budget->save();
    
    echo "Orçamento #{$budget->id} resetado para 'Analisando'\n";
    echo "User ID: {$budget->user_id}\n";
    echo "Created by: {$budget->created_by}\n\n";
    
    // Verificar configurações
    $settings = \App\Models\NotificationSetting::getForUser($budget->user_id);
    echo "Configurações do usuário:\n";
    echo "Priority order: {$settings->priority_order}\n";
    echo "Budget approved habilitado: " . (in_array('budget_approved', $settings->enabled_types) ? 'SIM' : 'NÃO') . "\n\n";
    
    // Contar notificações antes
    $notificationsBefore = \App\Models\Notification::where('user_id', $budget->user_id)
        ->where('budget_id', $budget->id)
        ->where('type', 'budget_approved')
        ->count();
    echo "Notificações existentes: {$notificationsBefore}\n\n";
    
    // Simular aprovação via PublicOrcamentoView
    echo "Simulando aprovação via link público (como no PublicOrcamentoView)...\n";
    
    if ($budget->status === 'Analisando') {
        $budget->status = 'Aprovado';
        $budget->save();
        
        // Disparar evento exatamente como no PublicOrcamentoView
        event(new \App\Events\BudgetApproved(
            $budget,
            $budget->user, // Usuário que criou o orçamento
            'Orçamento aprovado pelo cliente via link público',
            true // isPublicApproval = true
        ));
        
        echo "✅ Evento BudgetApproved disparado\n";
        echo "Status atualizado para: {$budget->status}\n";
        
        // Aguardar processamento
        echo "Aguardando 15 segundos para processamento da queue...\n";
        sleep(15);
        
        // Verificar notificações
        $notificationsAfter = \App\Models\Notification::where('user_id', $budget->user_id)
            ->where('budget_id', $budget->id)
            ->where('type', 'budget_approved')
            ->count();
        
        echo "\nNotificações após aprovação: {$notificationsAfter}\n";
        
        if ($notificationsAfter > $notificationsBefore) {
            echo "✅ SUCESSO: Nova notificação foi criada!\n";
            
            // Mostrar a notificação mais recente
            $notification = \App\Models\Notification::where('user_id', $budget->user_id)
                ->where('budget_id', $budget->id)
                ->where('type', 'budget_approved')
                ->latest()
                ->first();
                
            echo "\nDetalhes da notificação:\n";
            echo "ID: {$notification->id}\n";
            echo "Título: {$notification->title}\n";
            echo "Mensagem: {$notification->message}\n";
            echo "Prioridade: {$notification->priority}\n";
            echo "Lida: " . ($notification->is_read ? 'SIM' : 'NÃO') . "\n";
            echo "Criada em: {$notification->created_at}\n";
            
            echo "\n🎉 TESTE CONCLUÍDO COM SUCESSO!\n";
            echo "As notificações estão funcionando corretamente para aprovações via link público.\n";
        } else {
            echo "❌ ERRO: Nenhuma nova notificação foi criada\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}