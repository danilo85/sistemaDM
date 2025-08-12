<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFICAÇÃO DE JOBS FALHADOS ===\n\n";

try {
    // Verificar estrutura da tabela failed_jobs
    $columns = DB::select("SHOW COLUMNS FROM failed_jobs");
    echo "Colunas da tabela failed_jobs:\n";
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type})\n";
    }
    
    echo "\n";
    
    // Verificar jobs falhados
    $failedJobs = DB::table('failed_jobs')->get();
    echo "Total de jobs falhados: {$failedJobs->count()}\n\n";
    
    foreach ($failedJobs as $job) {
        echo "Job ID: {$job->id}\n";
        echo "UUID: {$job->uuid}\n";
        echo "Connection: {$job->connection}\n";
        echo "Queue: {$job->queue}\n";
        
        // Decodificar payload para ver qual evento falhou
        $payload = json_decode($job->payload, true);
        if ($payload && isset($payload['displayName'])) {
            echo "Evento: {$payload['displayName']}\n";
        }
        
        echo "Exception (primeiras 500 chars):\n";
        echo substr($job->exception, 0, 500) . "...\n";
        echo "\n" . str_repeat('-', 50) . "\n\n";
    }
    
} catch (Exception $e) {
    echo "Erro: {$e->getMessage()}\n";
}

echo "=== FIM DA VERIFICAÇÃO ===\n";