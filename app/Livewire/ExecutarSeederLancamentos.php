<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transacao;
use App\Models\Banco;
use App\Models\CartaoCredito;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExecutarSeederLancamentos extends Component
{
    public $executado = false;
    public $mensagem = '';
    public $erro = false;

    public function executarSeeder()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                $this->erro = true;
                $this->mensagem = 'Usuário não autenticado.';
                return;
            }

            // Buscar bancos e cartões existentes
            $bancos = Banco::where('user_id', $user->id)->get();
            $cartoes = CartaoCredito::where('user_id', $user->id)->get();
            $categorias = Categoria::where('user_id', $user->id)->get();

            if ($bancos->isEmpty()) {
                $this->erro = true;
                $this->mensagem = 'Nenhum banco encontrado. Execute primeiro o seeder de bancos.';
                return;
            }

            if ($cartoes->isEmpty()) {
                $this->erro = true;
                $this->mensagem = 'Nenhum cartão encontrado. Execute primeiro o seeder de cartões.';
                return;
            }

            if ($categorias->isEmpty()) {
                $this->erro = true;
                $this->mensagem = 'Nenhuma categoria encontrada. Execute primeiro o seeder de categorias.';
                return;
            }

            $categoriasReceita = $categorias->where('tipo', 'receita');
            $categoriasDespesa = $categorias->where('tipo', 'despesa');

            // Lançamentos para Bancos (10)
            $lancamentosBancos = [
                [
                    'descricao' => 'Salário mensal',
                    'valor' => 5500.00,
                    'tipo' => 'receita',
                    'categoria' => $categoriasReceita->where('nome', 'Salário')->first()?->id ?? $categoriasReceita->first()->id,
                    'dias_atras' => 1
                ],
                [
                    'descricao' => 'Freelance desenvolvimento web',
                    'valor' => 2800.00,
                    'tipo' => 'receita',
                    'categoria' => $categoriasReceita->where('nome', 'Freelance')->first()?->id ?? $categoriasReceita->first()->id,
                    'dias_atras' => 3
                ],
                [
                    'descricao' => 'Supermercado Extra',
                    'valor' => 450.80,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Alimentação')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 2
                ],
                [
                    'descricao' => 'Conta de luz CEMIG',
                    'valor' => 180.45,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Moradia')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 5
                ],
                [
                    'descricao' => 'Rendimento poupança',
                    'valor' => 125.30,
                    'tipo' => 'receita',
                    'categoria' => $categoriasReceita->where('nome', 'Investimentos')->first()?->id ?? $categoriasReceita->first()->id,
                    'dias_atras' => 7
                ],
                [
                    'descricao' => 'Posto de gasolina Shell',
                    'valor' => 320.00,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Transporte')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 4
                ],
                [
                    'descricao' => 'Venda de produto usado',
                    'valor' => 850.00,
                    'tipo' => 'receita',
                    'categoria' => $categoriasReceita->where('nome', 'Vendas')->first()?->id ?? $categoriasReceita->first()->id,
                    'dias_atras' => 10
                ],
                [
                    'descricao' => 'Farmácia Drogasil',
                    'valor' => 95.60,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Saúde')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 6
                ],
                [
                    'descricao' => 'Aluguel recebido',
                    'valor' => 1200.00,
                    'tipo' => 'receita',
                    'categoria' => $categoriasReceita->where('nome', 'Aluguel Recebido')->first()?->id ?? $categoriasReceita->first()->id,
                    'dias_atras' => 15
                ],
                [
                    'descricao' => 'Internet Vivo Fibra',
                    'valor' => 89.90,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Moradia')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 8
                ]
            ];

            // Lançamentos para Cartões (10)
            $lancamentosCartoes = [
                [
                    'descricao' => 'Amazon compras online',
                    'valor' => 280.50,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Compras')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 2
                ],
                [
                    'descricao' => 'Netflix assinatura',
                    'valor' => 45.90,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Entretenimento')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 12
                ],
                [
                    'descricao' => 'Restaurante Outback',
                    'valor' => 185.00,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Alimentação')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 3
                ],
                [
                    'descricao' => 'Uber viagens',
                    'valor' => 65.40,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Transporte')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 1
                ],
                [
                    'descricao' => 'Loja de roupas Zara',
                    'valor' => 420.00,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Compras')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 7
                ],
                [
                    'descricao' => 'Spotify Premium',
                    'valor' => 21.90,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Entretenimento')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 14
                ],
                [
                    'descricao' => 'Padaria do bairro',
                    'valor' => 35.80,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Alimentação')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 1
                ],
                [
                    'descricao' => 'Livraria Saraiva',
                    'valor' => 89.90,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Educação')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 9
                ],
                [
                    'descricao' => 'iFood delivery',
                    'valor' => 52.30,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Alimentação')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 4
                ],
                [
                    'descricao' => 'Posto Ipiranga',
                    'valor' => 280.00,
                    'tipo' => 'despesa',
                    'categoria' => $categoriasDespesa->where('nome', 'Transporte')->first()?->id ?? $categoriasDespesa->first()->id,
                    'dias_atras' => 6
                ]
            ];

            $lancamentosCriados = 0;

            // Criar lançamentos para bancos
            foreach ($lancamentosBancos as $index => $lancamento) {
                $banco = $bancos->get($index % $bancos->count());
                $dataLancamento = Carbon::now()->subDays($lancamento['dias_atras']);

                Transacao::create([
                    'user_id' => $user->id,
                    'descricao' => $lancamento['descricao'],
                    'valor' => $lancamento['valor'],
                    'data' => $dataLancamento,
                    'tipo' => $lancamento['tipo'],
                    'categoria_id' => $lancamento['categoria'],
                    'pago' => true,
                    'status' => 'confirmado',
                    'transacionavel_id' => $banco->id,
                    'transacionavel_type' => 'App\\Models\\Banco'
                ]);
                $lancamentosCriados++;
            }

            // Criar lançamentos para cartões
            foreach ($lancamentosCartoes as $index => $lancamento) {
                $cartao = $cartoes->get($index % $cartoes->count());
                $dataLancamento = Carbon::now()->subDays($lancamento['dias_atras']);

                Transacao::create([
                    'user_id' => $user->id,
                    'descricao' => $lancamento['descricao'],
                    'valor' => $lancamento['valor'],
                    'data' => $dataLancamento,
                    'tipo' => $lancamento['tipo'],
                    'categoria_id' => $lancamento['categoria'],
                    'pago' => false,
                    'status' => 'pendente',
                    'transacionavel_id' => $cartao->id,
                    'transacionavel_type' => 'App\\Models\\CartaoCredito'
                ]);
                $lancamentosCriados++;
            }

            $this->executado = true;
            $this->erro = false;
            $this->mensagem = "Sucesso! {$lancamentosCriados} lançamentos financeiros criados (10 bancários + 10 de cartão).";

        } catch (\Exception $e) {
            $this->erro = true;
            $this->mensagem = 'Erro ao executar seeder: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.executar-seeder-lancamentos');
    }
}