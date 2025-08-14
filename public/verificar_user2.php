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
    
    echo "<h2>Verificação do Usuário ID 2</h2>";
    
    // Verificar se existe usuário com ID 2
    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = 2");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p style='color: green;'>✅ Usuário com ID 2 encontrado:</p>";
        echo "<ul>";
        echo "<li>ID: " . $user['id'] . "</li>";
        echo "<li>Nome: " . $user['name'] . "</li>";
        echo "<li>Email: " . $user['email'] . "</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Usuário com ID 2 não encontrado.</p>";
        
        // Verificar todos os usuários disponíveis
        $stmt = $pdo->prepare("SELECT id, name, email FROM users ORDER BY id");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Usuários disponíveis:</h3>";
        if ($users) {
            echo "<ul>";
            foreach ($users as $u) {
                echo "<li>ID: {$u['id']} - Nome: {$u['name']} - Email: {$u['email']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Nenhum usuário encontrado na tabela users.</p>";
        }
    }
    
    // Verificar autores existentes
    echo "<h3>Autores existentes:</h3>";
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM autores");
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total de autores: " . $count['total'] . "</p>";
    
    if ($count['total'] > 0) {
        $stmt = $pdo->prepare("SELECT id, nome, email, user_id FROM autores ORDER BY id LIMIT 10");
        $stmt->execute();
        $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<ul>";
        foreach ($autores as $autor) {
            echo "<li>ID: {$autor['id']} - Nome: {$autor['nome']} - Email: {$autor['email']} - User ID: {$autor['user_id']}</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erro de conexão: " . $e->getMessage() . "</p>";
}

?>