<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Str;

// Busca o primeiro usuário ou cria um se não existir
$user = User::first();
if (!$user) {
    $user = User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);
}

echo "Criando 10 clientes de teste...\n";

// Array com dados de exemplo
$clientesData = [
    ['name' => 'Empresa ABC Ltda', 'contact_person' => 'João Silva', 'email' => 'joao@empresaabc.com', 'phone' => '(11) 99999-1111', 'is_complete' => true],
    ['name' => 'Tech Solutions', 'contact_person' => 'Maria Santos', 'email' => 'maria@techsolutions.com', 'phone' => '(11) 99999-2222', 'is_complete' => true],
    ['name' => 'Inovação Digital', 'contact_person' => 'Pedro Costa', 'email' => 'pedro@inovacao.com', 'phone' => '(11) 99999-3333', 'is_complete' => true],
    ['name' => 'StartUp XYZ', 'contact_person' => 'Ana Oliveira', 'email' => 'ana@startupxyz.com', 'phone' => '(11) 99999-4444', 'is_complete' => true],
    ['name' => 'Consultoria Pro', 'contact_person' => 'Carlos Mendes', 'email' => 'carlos@consultoriapro.com', 'phone' => '(11) 99999-5555', 'is_complete' => true],
    ['name' => 'Design Studio', 'contact_person' => 'Lucia Ferreira', 'email' => 'lucia@designstudio.com', 'phone' => '(11) 99999-6666', 'is_complete' => true],
    
    // Clientes incompletos (sem email)
    ['name' => 'Empresa Sem Email', 'contact_person' => 'Roberto Lima', 'email' => null, 'phone' => '(11) 99999-7777', 'is_complete' => false],
    ['name' => 'Negócios Locais', 'contact_person' => 'Sandra Rocha', 'email' => null, 'phone' => '(11) 99999-8888', 'is_complete' => false],
    
    // Cliente incompleto (sem telefone)
    ['name' => 'Empresa Sem Telefone', 'contact_person' => 'Fernando Alves', 'email' => 'fernando@semtelefone.com', 'phone' => null, 'is_complete' => false],
    
    // Cliente muito incompleto (só nome)
    ['name' => 'Cliente Básico', 'contact_person' => null, 'email' => null, 'phone' => null, 'is_complete' => false],
];

$count = 0;
foreach ($clientesData as $clienteData) {
    $cliente = Cliente::create([
        'user_id' => $user->id,
        'name' => $clienteData['name'],
        'contact_person' => $clienteData['contact_person'],
        'email' => $clienteData['email'],
        'phone' => $clienteData['phone'],
        'is_complete' => $clienteData['is_complete'],
        'extrato_ativo' => rand(0, 1) == 1,
        'token' => Str::uuid(),
    ]);
    
    $count++;
    echo "Cliente {$count}: {$cliente->name} criado com sucesso!\n";
}

echo "\n✅ Todos os 10 clientes de teste foram criados com sucesso!\n";
echo "Agora você pode verificar a interface dos cards na listagem de clientes.\n";