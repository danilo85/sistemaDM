<?php
require_once '../vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once '../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Verificar se já existem autores
    $totalAutores = App\Models\Autor::count();
    echo "<h2>Criação de Autores de Teste</h2>";
    echo "<p>Total de autores antes da criação: <strong>$totalAutores</strong></p>";
    
    // Dados dos 15 autores de teste
    $autoresData = [
        ['nome' => 'Ana Silva', 'email' => 'ana.silva@email.com', 'telefone' => '(11) 99999-0001', 'biografia' => 'Designer gráfica especializada em identidade visual e branding. Mais de 8 anos de experiência criando marcas memoráveis.', 'cor' => '#3B82F6'],
        ['nome' => 'Carlos Santos', 'email' => 'carlos.santos@email.com', 'telefone' => '(11) 99999-0002', 'biografia' => 'Desenvolvedor full-stack apaixonado por tecnologia e inovação. Especialista em React, Laravel e soluções escaláveis.', 'cor' => '#10B981'],
        ['nome' => 'Mariana Costa', 'email' => 'mariana.costa@email.com', 'telefone' => '(11) 99999-0003', 'biografia' => 'Fotógrafa profissional com foco em retratos corporativos e eventos. Captura momentos únicos com sensibilidade artística.', 'cor' => '#EC4899'],
        ['nome' => 'Roberto Lima', 'email' => 'roberto.lima@email.com', 'telefone' => '(11) 99999-0004', 'biografia' => 'Consultor de marketing digital e estratégias de crescimento. Ajuda empresas a expandir sua presença online.', 'cor' => '#F59E0B'],
        ['nome' => 'Juliana Oliveira', 'email' => 'juliana.oliveira@email.com', 'telefone' => '(11) 99999-0005', 'biografia' => 'Arquiteta e urbanista com expertise em projetos residenciais e comerciais sustentáveis.', 'cor' => '#8B5CF6'],
        ['nome' => 'Fernando Alves', 'email' => 'fernando.alves@email.com', 'telefone' => '(11) 99999-0006', 'biografia' => 'Redator publicitário e copywriter. Cria conteúdos persuasivos que convertem visitantes em clientes.', 'cor' => '#EF4444'],
        ['nome' => 'Camila Rodrigues', 'email' => 'camila.rodrigues@email.com', 'telefone' => '(11) 99999-0007', 'biografia' => 'UX/UI Designer focada em experiências digitais intuitivas e acessíveis. Especialista em design thinking.', 'cor' => '#06B6D4'],
        ['nome' => 'Lucas Pereira', 'email' => 'lucas.pereira@email.com', 'telefone' => '(11) 99999-0008', 'biografia' => 'Analista de dados e business intelligence. Transforma dados em insights estratégicos para tomada de decisões.', 'cor' => '#84CC16'],
        ['nome' => 'Beatriz Ferreira', 'email' => 'beatriz.ferreira@email.com', 'telefone' => '(11) 99999-0009', 'biografia' => 'Gestora de projetos certificada PMP. Especialista em metodologias ágeis e entrega de resultados.', 'cor' => '#F97316'],
        ['nome' => 'Diego Martins', 'email' => 'diego.martins@email.com', 'telefone' => '(11) 99999-0010', 'biografia' => 'Ilustrador e designer de personagens. Cria arte digital para games, animações e publicações.', 'cor' => '#A855F7'],
        ['nome' => 'Larissa Souza', 'email' => 'larissa.souza@email.com', 'telefone' => '(11) 99999-0011', 'biografia' => 'Consultora em recursos humanos e desenvolvimento organizacional. Especialista em cultura empresarial.', 'cor' => '#14B8A6'],
        ['nome' => 'Thiago Barbosa', 'email' => 'thiago.barbosa@email.com', 'telefone' => '(11) 99999-0012', 'biografia' => 'Engenheiro de software e arquiteto de sistemas. Constrói soluções robustas e escaláveis.', 'cor' => '#6366F1'],
        ['nome' => 'Gabriela Mendes', 'email' => 'gabriela.mendes@email.com', 'telefone' => '(11) 99999-0013', 'biografia' => 'Especialista em SEO e marketing de conteúdo. Otimiza sites para melhor posicionamento nos buscadores.', 'cor' => '#DC2626'],
        ['nome' => 'Rafael Cardoso', 'email' => 'rafael.cardoso@email.com', 'telefone' => '(11) 99999-0014', 'biografia' => 'Videomaker e editor profissional. Produz conteúdo audiovisual para marcas e eventos corporativos.', 'cor' => '#059669'],
        ['nome' => 'Priscila Gomes', 'email' => 'priscila.gomes@email.com', 'telefone' => '(11) 99999-0015', 'biografia' => 'Consultora financeira e analista de investimentos. Ajuda pessoas e empresas a alcançar estabilidade financeira.', 'cor' => '#7C3AED']
    ];
    
    $autoresCriados = 0;
    
    foreach ($autoresData as $autorData) {
        // Verificar se o autor já existe pelo email
        $autorExistente = App\Models\Autor::where('email', $autorData['email'])->first();
        
        if (!$autorExistente) {
            App\Models\Autor::create([
                'nome' => $autorData['nome'],
                'email' => $autorData['email'],
                'telefone' => $autorData['telefone'],
                'biografia' => $autorData['biografia'],
                'cor' => $autorData['cor'],
                'logo' => null,
                'is_complete' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $autoresCriados++;
        }
    }
    
    $totalAutoresDepois = App\Models\Autor::count();
    
    echo "<h3>Resultado:</h3>";
    echo "<p>Autores criados: <strong>$autoresCriados</strong></p>";
    echo "<p>Total de autores após criação: <strong>$totalAutoresDepois</strong></p>";
    
    if ($autoresCriados > 0) {
        echo "<p style='color: green;'>✅ Autores de teste criados com sucesso!</p>";
        
        echo "<h3>Autores criados:</h3>";
        $autoresCriados = App\Models\Autor::orderBy('created_at', 'desc')->take($autoresCriados)->get();
        echo "<ul>";
        foreach ($autoresCriados as $autor) {
            echo "<li><strong>{$autor->nome}</strong> - {$autor->email} - Cor: <span style='color: {$autor->cor};'>●</span> {$autor->cor}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ Nenhum autor novo foi criado (todos já existiam).</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>