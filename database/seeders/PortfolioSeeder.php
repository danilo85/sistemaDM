<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Testimonial;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar serviços iniciais
        Service::create([
            'title' => 'Ilustração Digital',
            'description' => 'Criação de ilustrações personalizadas para livros, jogos e materiais educativos',
            'icon' => 'palette',
            'order_index' => 1,
            'active' => true
        ]);

        Service::create([
            'title' => 'Diagramação',
            'description' => 'Layout e diagramação profissional para publicações impressas e digitais',
            'icon' => 'layout',
            'order_index' => 2,
            'active' => true
        ]);

        Service::create([
            'title' => 'Design de Jogos',
            'description' => 'Desenvolvimento visual completo para jogos educativos e entretenimento',
            'icon' => 'gamepad',
            'order_index' => 3,
            'active' => true
        ]);

        // Criar depoimentos iniciais
        Testimonial::create([
            'client_name' => 'Maria Silva',
            'content' => 'Trabalho excepcional! As ilustrações ficaram perfeitas para nosso livro infantil.',
            'rating' => 5,
            'active' => true
        ]);

        Testimonial::create([
            'client_name' => 'João Santos',
            'content' => 'Profissionalismo e criatividade em cada detalhe. Recomendo!',
            'rating' => 5,
            'active' => true
        ]);

        Testimonial::create([
            'client_name' => 'Ana Costa',
            'content' => 'Superou nossas expectativas. O jogo educativo ficou incrível!',
            'rating' => 5,
            'active' => true
        ]);
    }
}