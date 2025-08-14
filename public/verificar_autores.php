<?php
require_once '../vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once '../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Verificar quantos autores existem
    $totalAutores = App\Models\Autor::count();
    echo "<h2>Verificação de Autores</h2>";
    echo "<p>Total de autores na tabela: <strong>$totalAutores</strong></p>";
    
    if ($totalAutores > 0) {
        echo "<h3>Primeiros 5 autores:</h3>";
        $autores = App\Models\Autor::take(5)->get();
        echo "<ul>";
        foreach ($autores as $autor) {
            echo "<li>ID: {$autor->id} - Nome: {$autor->nome} - Email: {$autor->email}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>Nenhum autor encontrado na tabela.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
}
?>