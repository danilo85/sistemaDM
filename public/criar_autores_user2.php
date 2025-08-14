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
    
    echo "<h2>Criação de 15 Autores de Teste para User ID 2</h2>";
    
    // Verificar se existe usuário com ID 2
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = 2");
    $stmt->execute();
    $userExists = $stmt->fetch();
    
    $userId = $userExists ? 2 : 1; // Se não existir user 2, usar user 1
    
    echo "<p>Usando User ID: $userId</p>";
    
    // Limpar autores existentes do usuário
    $stmt = $pdo->prepare("DELETE FROM autores WHERE user_id = ?");
    $stmt->execute([$userId]);
    echo "<p>Autores anteriores removidos.</p>";
    
    // Dados dos autores de teste
    $autores = [
        ['nome' => 'Ana Silva', 'email' => 'ana.silva@email.com', 'telefone' => '(11) 98765-4321', 'biografia' => 'Escritora especializada em ficção científica e fantasia. Autora de diversos best-sellers nacionais.', 'cor' => '#3B82F6'],
        ['nome' => 'Carlos Santos', 'email' => 'carlos.santos@email.com', 'telefone' => '(21) 97654-3210', 'biografia' => 'Jornalista e autor de livros de não-ficção sobre política e sociedade brasileira.', 'cor' => '#10B981'],
        ['nome' => 'Beatriz Costa', 'email' => 'beatriz.costa@email.com', 'telefone' => '(31) 96543-2109', 'biografia' => 'Poeta e cronista, vencedora de múltiplos prêmios literários nacionais e internacionais.', 'cor' => '#EC4899'],
        ['nome' => 'Diego Oliveira', 'email' => 'diego.oliveira@email.com', 'telefone' => '(41) 95432-1098', 'biografia' => 'Autor de literatura infantojuvenil, com mais de 20 livros publicados e traduzidos.', 'cor' => '#F59E0B'],
        ['nome' => 'Elena Rodriguez', 'email' => 'elena.rodriguez@email.com', 'telefone' => '(51) 94321-0987', 'biografia' => 'Romancista histórica, especialista em narrativas ambientadas no Brasil colonial.', 'cor' => '#EF4444'],
        ['nome' => 'Fernando Lima', 'email' => 'fernando.lima@email.com', 'telefone' => '(61) 93210-9876', 'biografia' => 'Escritor de suspense e thriller, com obras adaptadas para cinema e televisão.', 'cor' => '#8B5CF6'],
        ['nome' => 'Gabriela Mendes', 'email' => 'gabriela.mendes@email.com', 'telefone' => '(71) 92109-8765', 'biografia' => 'Autora de autoajuda e desenvolvimento pessoal, palestrante internacional.', 'cor' => '#06B6D4'],
        ['nome' => 'Henrique Alves', 'email' => 'henrique.alves@email.com', 'telefone' => '(81) 91098-7654', 'biografia' => 'Biógrafo e historiador, especializado em personalidades brasileiras do século XX.', 'cor' => '#84CC16'],
        ['nome' => 'Isabela Ferreira', 'email' => 'isabela.ferreira@email.com', 'telefone' => '(85) 90987-6543', 'biografia' => 'Escritora de contos e novelas, professora de escrita criativa em universidades.', 'cor' => '#F97316'],
        ['nome' => 'João Pereira', 'email' => 'joao.pereira@email.com', 'telefone' => '(11) 89876-5432', 'biografia' => 'Autor de crônicas esportivas e livros sobre futebol brasileiro.', 'cor' => '#6366F1'],
        ['nome' => 'Larissa Campos', 'email' => 'larissa.campos@email.com', 'telefone' => '(21) 88765-4321', 'biografia' => 'Novelista contemporânea, explorando temas de identidade e relacionamentos modernos.', 'cor' => '#14B8A6'],
        ['nome' => 'Marcos Ribeiro', 'email' => 'marcos.ribeiro@email.com', 'telefone' => '(31) 87654-3210', 'biografia' => 'Escritor de ficção científica hard, engenheiro e divulgador científico.', 'cor' => '#F472B6'],
        ['nome' => 'Natália Souza', 'email' => 'natalia.souza@email.com', 'telefone' => '(41) 86543-2109', 'biografia' => 'Autora de literatura LGBTQ+, ativista pelos direitos humanos e diversidade.', 'cor' => '#FBBF24'],
        ['nome' => 'Otávio Martins', 'email' => 'otavio.martins@email.com', 'telefone' => '(51) 85432-1098', 'biografia' => 'Dramaturgo e roteirista, com peças teatrais premiadas em festivais nacionais.', 'cor' => '#A855F7'],
        ['nome' => 'Patrícia Gomes', 'email' => 'patricia.gomes@email.com', 'telefone' => '(61) 84321-0987', 'biografia' => 'Escritora de memórias e ensaios, jornalista cultural com 30 anos de carreira.', 'cor' => '#22C55E']
    ];
    
    $sucessos = 0;
    
    foreach ($autores as $autor) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO autores (nome, email, telefone, biografia, cor, user_id, is_complete, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
            ");
            
            $stmt->execute([
                $autor['nome'],
                $autor['email'],
                $autor['telefone'],
                $autor['biografia'],
                $autor['cor'],
                $userId
            ]);
            
            $sucessos++;
            echo "<p style='color: green;'>✅ Autor criado: {$autor['nome']}</p>";
            
        } catch (PDOException $e) {
            echo "<p style='color: red;'>❌ Erro ao criar {$autor['nome']}: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>Resumo:</h3>";
    echo "<p><strong>$sucessos autores criados com sucesso!</strong></p>";
    
    // Verificar autores criados
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM autores WHERE user_id = ?");
    $stmt->execute([$userId]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<p>Total de autores para User ID $userId: " . $count['total'] . "</p>";
    
    // Listar alguns autores criados
    $stmt = $pdo->prepare("SELECT nome, email, cor FROM autores WHERE user_id = ? ORDER BY id LIMIT 5");
    $stmt->execute([$userId]);
    $autoresCriados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($autoresCriados) {
        echo "<h4>Primeiros 5 autores criados:</h4>";
        echo "<ul>";
        foreach ($autoresCriados as $autor) {
            echo "<li style='color: {$autor['cor']};'>{$autor['nome']} - {$autor['email']}</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erro de conexão: " . $e->getMessage() . "</p>";
}

?>