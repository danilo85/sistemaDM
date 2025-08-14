<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categorias de Receitas (7)
        $receitas = [
            [
                'nome' => 'Salário',
                'descricao' => 'Salário mensal e benefícios trabalhistas',
                'tipo' => 'receita'
            ],
            [
                'nome' => 'Freelance',
                'descricao' => 'Trabalhos freelancer e projetos independentes',
                'tipo' => 'receita'
            ],
            [
                'nome' => 'Vendas',
                'descricao' => 'Receitas de vendas de produtos ou serviços',
                'tipo' => 'receita'
            ],
            [
                'nome' => 'Investimentos',
                'descricao' => 'Rendimentos de investimentos e aplicações',
                'tipo' => 'receita'
            ],
            [
                'nome' => 'Aluguel Recebido',
                'descricao' => 'Receitas de aluguel de imóveis',
                'tipo' => 'receita'
            ],
            [
                'nome' => 'Comissões',
                'descricao' => 'Comissões e bonificações recebidas',
                'tipo' => 'receita'
            ],
            [
                'nome' => 'Outros Rendimentos',
                'descricao' => 'Outras fontes de receita diversas',
                'tipo' => 'receita'
            ]
        ];

        // Categorias de Despesas (8)
        $despesas = [
            [
                'nome' => 'Alimentação',
                'descricao' => 'Gastos com alimentação, supermercado e restaurantes',
                'tipo' => 'despesa'
            ],
            [
                'nome' => 'Transporte',
                'descricao' => 'Combustível, transporte público e manutenção veicular',
                'tipo' => 'despesa'
            ],
            [
                'nome' => 'Moradia',
                'descricao' => 'Aluguel, financiamento, condomínio e contas da casa',
                'tipo' => 'despesa'
            ],
            [
                'nome' => 'Saúde',
                'descricao' => 'Plano de saúde, medicamentos e consultas médicas',
                'tipo' => 'despesa'
            ],
            [
                'nome' => 'Educação',
                'descricao' => 'Cursos, livros, mensalidades e material escolar',
                'tipo' => 'despesa'
            ],
            [
                'nome' => 'Lazer',
                'descricao' => 'Entretenimento, viagens e atividades recreativas',
                'tipo' => 'despesa'
            ],
            [
                'nome' => 'Vestuário',
                'descricao' => 'Roupas, calçados e acessórios',
                'tipo' => 'despesa'
            ],
            [
                'nome' => 'Outros Gastos',
                'descricao' => 'Despesas diversas não categorizadas',
                'tipo' => 'despesa'
            ]
        ];

        // Inserir categorias de receitas
        foreach ($receitas as $receita) {
            Categoria::create($receita);
        }

        // Inserir categorias de despesas
        foreach ($despesas as $despesa) {
            Categoria::create($despesa);
        }
    }
}