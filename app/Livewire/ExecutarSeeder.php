<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class ExecutarSeeder extends Component
{
    public $mensagem = '';
    public $executado = false;

    public function executarSeeder()
    {
        try {
            // Verificar se já existem categorias
            $categoriasExistentes = Categoria::count();
            
            if ($categoriasExistentes > 0) {
                $this->mensagem = 'Já existem ' . $categoriasExistentes . ' categorias cadastradas.';
                $this->executado = true;
                return;
            }

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

            DB::beginTransaction();

            // Inserir categorias de receitas
            foreach ($receitas as $receita) {
                Categoria::create($receita);
            }

            // Inserir categorias de despesas
            foreach ($despesas as $despesa) {
                Categoria::create($despesa);
            }

            DB::commit();

            $this->mensagem = '15 categorias financeiras foram criadas com sucesso! (7 receitas e 8 despesas)';
            $this->executado = true;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->mensagem = 'Erro ao criar categorias: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.executar-seeder');
    }
}