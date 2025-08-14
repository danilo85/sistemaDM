<?php

// Script para adicionar colunas logo e cor à tabela autores
header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=sistemadm', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado ao banco de dados com sucesso!\n";
    
    // Verificar se as colunas já existem
    $stmt = $pdo->query("SHOW COLUMNS FROM autores");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Colunas atuais da tabela autores:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    // Adicionar coluna logo se não existir
    if (!in_array('logo', $columns)) {
        echo "\nAdicionando coluna 'logo'...\n";
        $pdo->exec("ALTER TABLE autores ADD COLUMN logo VARCHAR(255) NULL");
        echo "Coluna 'logo' adicionada com sucesso!\n";
    } else {
        echo "\nColuna 'logo' já existe.\n";
    }
    
    // Adicionar coluna cor se não existir
    if (!in_array('cor', $columns)) {
        echo "\nAdicionando coluna 'cor'...\n";
        $pdo->exec("ALTER TABLE autores ADD COLUMN cor VARCHAR(7) DEFAULT '#ffffff'");
        echo "Coluna 'cor' adicionada com sucesso!\n";
    } else {
        echo "\nColuna 'cor' já existe.\n";
    }
    
    // Verificar estrutura final
    echo "\nVerificando estrutura final da tabela autores:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM autores");
    $finalColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($finalColumns as $column) {
        echo "- {$column['Field']} ({$column['Type']}) - {$column['Null']} - Default: {$column['Default']}\n";
    }
    
    echo "\nScript executado com sucesso!\n";
    echo "\nAgora você pode testar o upload de imagem e seleção de cor nos autores.\n";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
?>