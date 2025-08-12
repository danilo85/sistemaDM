<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Orcamento;
use App\Models\User;
use App\Events\BudgetApproved;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Models\Notification;
use Illuminate\Support\Facades\Artisan;

echo "=== TESTE FINAL DE APROVAÇÃO VIA LINK PÚBLICO ===\n\n";

// Buscar um orçamento para testar
$budget = Orcamento::first();
if (!$budget) {
    echo "❌ Nenhum orçamento encontrado\n";
    exit(1);
}

echo "Usando orçamento ID: {$budget->id}\n";
echo "Responsável: {$budget->user->name} (ID: {$budget->user_id})\n\n";

// Verificar notificações antes
$notificationsBefore = Notification::count();
echo "Notificações antes: {$notificationsBefore}\n";

// Verificar jobs antes
$jobsBefore = \DB::table('jobs')->count();
echo "Jobs na queue antes: {$jobsBefore}\n\n";

// Disparar evento de aprovação via link público
echo "Disparando evento BudgetApproved com isPublicApproval=true...\n";
event(new BudgetApproved($budget, $budget->user, true));

// Verificar jobs depois
$jobsAfter = \DB::table('jobs')->count();
echo "Jobs na queue depois: {$jobsAfter}\n";

if ($jobsAfter > $jobsBefore) {
    echo "✅ Evento foi enfileirado com sucesso!\n\n";
    
    // Processar a queue
    echo "Processando queue...\n";
    \Artisan::call('queue:work', ['--stop-when-empty' => true]);
    echo "Queue processada.\n\n";
    
    // Verificar notificações depois
    $notificationsAfter = Notification::count();
    echo "Notificações depois: {$notificationsAfter}\n";
    
    if ($notificationsAfter > $notificationsBefore) {
        echo "✅ Nova notificação criada com sucesso!\n";
        
        // Mostrar a última notificação
        $lastNotification = Notification::latest()->first();
        echo "Última notificação: ID {$lastNotification->id}, User {$lastNotification->user_id}, Type: {$lastNotification->type}, Title: {$lastNotification->title}\n";
    } else {
        echo "❌ Nenhuma nova notificação foi criada\n";
    }
} else {
    echo "❌ Evento não foi enfileirado\n";
}

echo "\n=== FIM DO TESTE ===\n";