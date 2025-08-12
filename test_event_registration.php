<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE DE REGISTRO DE EVENTO ===\n\n";

try {
    // Verificar se o listener está registrado
    $events = app('events');
    $listeners = $events->getListeners('App\\Events\\BudgetApproved');
    
    echo "Listeners registrados para BudgetApproved:\n";
    foreach ($listeners as $listener) {
        if (is_string($listener)) {
            echo "  - {$listener}\n";
        } elseif (is_array($listener) && count($listener) >= 2) {
            echo "  - {$listener[0]}@{$listener[1]}\n";
        } else {
            echo "  - " . gettype($listener) . "\n";
        }
    }
    
    if (empty($listeners)) {
        echo "  Nenhum listener registrado!\n";
    }
    
    echo "\n";
    
    // Verificar EventServiceProvider
    echo "Verificando EventServiceProvider...\n";
    $provider = new \App\Providers\EventServiceProvider($app);
    $listens = $provider->listens();
    
    if (isset($listens['App\\Events\\BudgetApproved'])) {
        echo "BudgetApproved está registrado no EventServiceProvider:\n";
        foreach ($listens['App\\Events\\BudgetApproved'] as $listener) {
            echo "  - {$listener}\n";
        }
    } else {
        echo "BudgetApproved NÃO está registrado no EventServiceProvider!\n";
    }
    
    echo "\n";
    
    // Testar disparo direto do evento
    echo "Testando disparo direto do evento...\n";
    
    $budget = \App\Models\Orcamento::first();
    if (!$budget) {
        echo "Nenhum orçamento encontrado.\n";
        exit;
    }
    
    echo "Usando orçamento ID: {$budget->id}\n";
    
    // Contar jobs antes
    $jobsBefore = \DB::table('jobs')->count();
    echo "Jobs na queue antes: {$jobsBefore}\n";
    
    // Disparar evento
    $event = new \App\Events\BudgetApproved(
        $budget,
        $budget->user,
        'Teste de registro de evento',
        true
    );
    
    event($event);
    
    // Contar jobs depois
    $jobsAfter = \DB::table('jobs')->count();
    echo "Jobs na queue depois: {$jobsAfter}\n";
    
    if ($jobsAfter > $jobsBefore) {
        echo "✅ Evento foi enfileirado com sucesso!\n";
        
        // Verificar o último job
        $lastJob = \DB::table('jobs')->latest('id')->first();
        if ($lastJob) {
            $payload = json_decode($lastJob->payload, true);
            echo "Último job: {$payload['displayName']}\n";
        }
    } else {
        echo "❌ Evento não foi enfileirado!\n";
    }
    
} catch (Exception $e) {
    echo "Erro: {$e->getMessage()}\n";
    echo "Trace: {$e->getTraceAsString()}\n";
}

echo "\n=== FIM DO TESTE ===\n";