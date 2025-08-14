<?php
// Script para criar 15 autores de teste no banco sistemadm
// Configuração do banco de dados
$host = 'localhost';
$dbname = 'sistemadm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Conectado ao banco de dados: $dbname</h2>";
    
    // Verificar se user_id 2 existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = 2");
    $stmt->execute();
    $user_exists = $stmt->fetch();
    
    $user_id = $user_exists ? 2 : 1;
    echo "<p>Usando user_id: $user_id</p>";
    
    // Array com dados dos 15 autores
    $autores = [
        ['name' => 'Ana Silva', 'email' => 'ana.silva@email.com', 'contact' => '(11) 98765-4321', 'bio' => 'Escritora especializada em ficção científica com mais de 10 anos de experiência.', 'cor' => '#3B82F6'],
        ['name' => 'Carlos Santos', 'email' => 'carlos.santos@email.com', 'contact' => '(21) 99876-5432', 'bio' => 'Autor de romances históricos e pesquisador de literatura brasileira.', 'cor' => '#10B981'],
        ['name' => 'Maria Oliveira', 'email' => 'maria.oliveira@email.com', 'contact' => '(31) 98765-1234', 'bio' => 'Poetisa contemporânea e professora de literatura em universidades.', 'cor' => '#EC4899'],
        ['name' => 'João Pereira', 'email' => 'joao.pereira@email.com', 'contact' => '(41) 97654-3210', 'bio' => 'Escritor de crônicas e colunista de grandes jornais nacionais.', 'cor' => '#F59E0B'],
        ['name' => 'Fernanda Costa', 'email' => 'fernanda.costa@email.com', 'contact' => '(51) 96543-2109', 'bio' => 'Autora de livros infantis premiada nacionalmente.', 'cor' => '#EF4444'],
        ['name' => 'Roberto Lima', 'email' => 'roberto.lima@email.com', 'contact' => '(61) 95432-1098', 'bio' => 'Dramaturgo e roteirista com peças encenadas em todo o país.', 'cor' => '#8B5CF6'],
        ['name' => 'Juliana Alves', 'email' => 'juliana.alves@email.com', 'contact' => '(71) 94321-0987', 'bio' => 'Jornalista e escritora de não-ficção, especialista em biografias.', 'cor' => '#06B6D4'],
        ['name' => 'Pedro Rodrigues', 'email' => 'pedro.rodrigues@email.com', 'contact' => '(81) 93210-9876', 'bio' => 'Autor de thrillers psicológicos e professor de escrita criativa.', 'cor' => '#84CC16'],
        ['name' => 'Camila Ferreira', 'email' => 'camila.ferreira@email.com', 'contact' => '(85) 92109-8765', 'bio' => 'Escritora de romance contemporâneo e blogueira literária.', 'cor' => '#F97316'],
        ['name' => 'Lucas Martins', 'email' => 'lucas.martins@email.com', 'contact' => '(11) 91098-7654', 'bio' => 'Autor de fantasia épica e criador de mundos ficcionais complexos.', 'cor' => '#6366F1'],
        ['name' => 'Beatriz Souza', 'email' => 'beatriz.souza@email.com', 'contact' => '(21) 90987-6543', 'bio' => 'Poetisa e tradutora de literatura estrangeira para o português.', 'cor' => '#14B8A6'],
        ['name' => 'Rafael Gomes', 'email' => 'rafael.gomes@email.com', 'contact' => '(31) 89876-5432', 'bio' => 'Escritor de ficção histórica e pesquisador de arquivos nacionais.', 'cor' => '#F472B6'],
        ['name' => 'Larissa Dias', 'email' => 'larissa.dias@email.com', 'contact' => '(41) 88765-4321', 'bio' => 'Autora de contos urbanos e organizadora de antologias literárias.', 'cor' => '#FBBF24'],
        ['name' => 'Thiago Barbosa', 'email' => 'thiago.barbosa@email.com', 'contact' => '(51) 87654-3210', 'bio' => 'Romancista e crítico literário com publicações em revistas especializadas.', 'cor' => '#EF4444'],
        ['name' => 'Gabriela Ribeiro', 'email' => 'gabriela.ribeiro@email.com', 'contact' => '(61) 86543-2109', 'bio' => 'Escritora de memórias e autora de livros de autoajuda.', 'cor' => '#FFFFFF']
    ];
    
    // Inserir cada autor
    $sql = "INSERT INTO autores (user_id, name, email, contact, bio, cor, is_complete, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    
    $count = 0;
    foreach ($autores as $autor) {
        $stmt->execute([
            $user_id,
            $autor['name'],
            $autor['email'],
            $autor['contact'],
            $autor['bio'],
            $autor['cor']
        ]);
        $count++;
        echo "<p>✓ Autor criado: {$autor['name']} (Cor: {$autor['cor']})</p>";
    }
    
    echo "<h3>✅ Sucesso! $count autores foram criados no banco '$dbname'.</h3>";
    
    // Verificar quantos autores existem agora
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM autores WHERE user_id = $user_id");
    $result = $stmt->fetch();
    echo "<p><strong>Total de autores para user_id $user_id: {$result['total']}</strong></p>";
    
} catch (PDOException $e) {
    echo "<h3>❌ Erro de conexão: " . $e->getMessage() . "</h3>";
}
?>