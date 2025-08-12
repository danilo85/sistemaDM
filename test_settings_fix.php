<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE DE CONFIGURAÇÕES DE NOTIFICAÇÃO ===\n\n";

try {
    // Testar salvamento de configurações
    $settings = \App\Models\NotificationSetting::getForUser(1);
    
    echo "Configurações atuais do usuário 1:\n";
    echo "Priority order: {$settings->priority_order}\n";
    echo "Enabled types: " . json_encode($settings->enabled_types) . "\n\n";
    
    // Testar mudança para high_first
    echo "Testando mudança para 'high_first'...\n";
    $settings->priority_order = 'high_first';
    $settings->save();
    echo "✅ Salvo com sucesso!\n\n";
    
    // Testar mudança para low_first
    echo "Testando mudança para 'low_first'...\n";
    $settings->priority_order = 'low_first';
    $settings->save();
    echo "✅ Salvo com sucesso!\n\n";
    
    // Testar mudança para chronological
    echo "Testando mudança para 'chronological'...\n";
    $settings->priority_order = 'chronological';
    $settings->save();
    echo "✅ Salvo com sucesso!\n\n";
    
    // Verificar configurações finais
    $settings->refresh();
    echo "Configurações finais:\n";
    echo "Priority order: {$settings->priority_order}\n";
    echo "Enabled types: " . json_encode($settings->enabled_types) . "\n";
    
    echo "\n✅ TODOS OS TESTES PASSARAM! O erro de truncamento foi corrigido.\n";
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}