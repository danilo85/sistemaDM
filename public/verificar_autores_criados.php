<?php

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'sistemadm';
$username = 'root';
$password = '';

try {
    // Conectar ao banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Verificação dos Autores Criados</h2>";
    
    // Verificar total de autores
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM autores");
    $stmt->execute();
    $totalGeral = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<p><strong>Total geral de autores:</strong> " . $totalGeral['total'] . "</p>";
    
    // Verificar autores por user_id
    $stmt = $pdo->prepare("SELECT user_id, COUNT(*) as total FROM autores GROUP BY user_id ORDER BY user_id");
    $stmt->execute();
    $autoresPorUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Autores por User ID:</h3>";
    if ($autoresPorUser) {
        echo "<ul>";
        foreach ($autoresPorUser as $grupo) {
            echo "<li>User ID {$grupo['user_id']}: {$grupo['total']} autores</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Nenhum autor encontrado!</p>";
    }
    
    // Verificar especificamente user_id 2
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM autores WHERE user_id = 2");
    $stmt->execute();
    $totalUser2 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Autores do User ID 2:</h3>";
    echo "<p><strong>Total:</strong> " . $totalUser2['total'] . " autores</p>";
    
    if ($totalUser2['total'] > 0) {
        // Listar todos os autores do user_id 2
        $stmt = $pdo->prepare("SELECT id, nome, email, telefone, cor, is_complete FROM autores WHERE user_id = 2 ORDER BY id");
        $stmt->execute();
        $autoresUser2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h4>Lista completa dos autores (User ID 2):</h4>";
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Cor</th><th>Completo</th>";
        echo "</tr>";
        
        foreach ($autoresUser2 as $autor) {
            $completo = $autor['is_complete'] ? '✅' : '❌';
            echo "<tr>";
            echo "<td>{$autor['id']}</td>";
            echo "<td style='color: {$autor['cor']};'><strong>{$autor['nome']}</strong></td>";
            echo "<td>{$autor['email']}</td>";
            echo "<td>{$autor['telefone']}</td>";
            echo "<td><span style='background-color: {$autor['cor']}; color: white; padding: 2px 8px; border-radius: 3px;'>{$autor['cor']}</span></td>";
            echo "<td>{$completo}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Verificar cores únicas
        $stmt = $pdo->prepare("SELECT DISTINCT cor FROM autores WHERE user_id = 2");
        $stmt->execute();
        $cores = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "<h4>Cores utilizadas:</h4>";
        echo "<div style='display: flex; gap: 10px; flex-wrap: wrap;'>";
        foreach ($cores as $cor) {
            echo "<span style='background-color: $cor; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px;'>$cor</span>";
        }
        echo "</div>";
        
    } else {
        echo "<p style='color: red;'>❌ Nenhum autor encontrado para User ID 2!</p>";
        
        // Verificar se existe user_id 1
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM autores WHERE user_id = 1");
        $stmt->execute();
        $totalUser1 = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($totalUser1['total'] > 0) {
            echo "<p style='color: orange;'>ℹ️ Encontrados {$totalUser1['total']} autores para User ID 1</p>";
        }
    }
    
    echo "<hr>";
    echo "<p><em>Verificação concluída em " . date('d/m/Y H:i:s') . "</em></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erro de conexão: " . $e->getMessage() . "</p>";
}

?>