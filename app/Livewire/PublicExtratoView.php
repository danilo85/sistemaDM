<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Pagamento;
use Carbon\Carbon;

class PublicExtratoView extends Component
{
    public Cliente $cliente;
    public $user;
    public $dataInicio;
    public $dataFim;
    
    // KPIs
    public $totalEmProjetos = 0;
    public $totalPago = 0;
    public $saldoDevedor = 0;
    public $totalOrcamentos = 0;
    public $orcamentosAprovados = 0;
    public $taxaAprovacao = 0;
    
    // Dados
    public $debitos = [];
    public $creditos = [];
    
    public function mount(Cliente $cliente)
    {
        // Carregar o cliente com o usuário
        $this->cliente = $cliente->load('user');
        
        // Verificar se o extrato está ativo
        if (!$this->cliente->extrato_ativo) {
            abort(403, 'Este extrato não está mais disponível.');
        }
        
        // Carregar dados do usuário
        $this->user = $this->cliente->user;
        
        // Define período padrão (últimos 12 meses)
        $this->dataFim = now()->format('Y-m-d');
        $this->dataInicio = now()->subMonths(12)->format('Y-m-d');
        
        $this->calcularExtrato();
    }
    
    public function calcularExtrato()
    {
        $dataInicio = Carbon::parse($this->dataInicio);
        $dataFim = Carbon::parse($this->dataFim)->endOfDay();
        
        // Buscar orçamentos aprovados no período
        $orcamentosAprovados = $this->cliente->orcamentos()
            ->where('status', 'aprovado')
            ->whereBetween('data_emissao', [$dataInicio, $dataFim])
            ->with(['pagamentos'])
            ->get();
            
        // Buscar todos os orçamentos para estatísticas
        $todosOrcamentos = $this->cliente->orcamentos()
            ->whereBetween('data_emissao', [$dataInicio, $dataFim])
            ->get();
            
        // Buscar pagamentos no período
        $pagamentos = Pagamento::whereHas('orcamento', function($query) {
                $query->where('cliente_id', $this->cliente->id);
            })
            ->whereBetween('data_pagamento', [$dataInicio, $dataFim])
            ->with(['orcamento'])
            ->get();
        
        // Calcular KPIs
        $this->totalEmProjetos = $orcamentosAprovados->sum('valor_total');
        $this->totalPago = $pagamentos->sum('valor');
        $this->saldoDevedor = $this->totalEmProjetos - $this->totalPago;
        $this->totalOrcamentos = $todosOrcamentos->count();
        $this->orcamentosAprovados = $orcamentosAprovados->count();
        $this->taxaAprovacao = $this->totalOrcamentos > 0 
            ? round(($this->orcamentosAprovados / $this->totalOrcamentos) * 100, 1) 
            : 0;
        
        // Preparar débitos (orçamentos aprovados)
        $this->debitos = $orcamentosAprovados->map(function($orcamento) {
            return [
                'id' => $orcamento->id,
                'numero' => $orcamento->numero,
                'titulo' => $orcamento->titulo,
                'data' => $orcamento->data_emissao->format('d/m/Y'),
                'valor' => $orcamento->valor_total,
                'status' => $orcamento->status,
                'valor_pago' => $orcamento->pagamentos->sum('valor'),
                'saldo_devedor' => $orcamento->valor_total - $orcamento->pagamentos->sum('valor')
            ];
        })->toArray();
        
        // Preparar créditos (pagamentos)
        $this->creditos = $pagamentos->map(function($pagamento) {
            return [
                'id' => $pagamento->id,
                'orcamento_numero' => $pagamento->orcamento->numero ?? 'N/A',
                'orcamento_titulo' => $pagamento->orcamento->titulo ?? 'N/A',
                'data' => $pagamento->data_pagamento->format('d/m/Y'),
                'valor' => $pagamento->valor,
                'descricao' => $pagamento->descricao,
                'metodo' => $pagamento->metodo_pagamento
            ];
        })->toArray();
    }
    
    public function render()
    {
        return view('livewire.public-extrato-view')
            ->layout('layouts.public'); // Layout público sem autenticação
    }
}
