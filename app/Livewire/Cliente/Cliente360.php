<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Pagamento;
use Livewire\Component;

class Cliente360 extends Component
{
    public Cliente $cliente;

    // Propriedades para os KPIs expandidos
    public float $totalEmProjetos = 0;
    public float $totalPago = 0;
    public float $saldoDevedor = 0;
    public int $orcamentosEnviados = 0;
    public float $taxaAprovacao = 0;
    public float $totalAReceber = 0;

    // Propriedades para o filtro de data
    public $dataInicio;
    public $dataFim;

    // Propriedades para o sistema de abas
    public string $activeTab = 'pagamentos';

    // Filtros para a aba Orçamentos
    public string $orcamentoStatusFilter = 'todos';
    public string $orcamentoOrderBy = 'created_at';
    public string $orcamentoOrderDirection = 'desc';

    // Coleções para as tabelas
    public $orcamentos;
    public $pagamentos;
    public $orcamentosDetalhados;
    public $timelineEventos;

    public function mount()
    {
        $this->dataFim = now()->format('Y-m-d');
        $this->dataInicio = now()->subYear()->format('Y-m-d');
        $this->calcularKPIs();
        $this->carregarDados();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dataInicio', 'dataFim'])) {
            $this->calcularKPIs();
            $this->carregarDados();
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        
        // Carrega dados específicos da aba se necessário
        if ($tab === 'orcamentos' && empty($this->orcamentosDetalhados)) {
            $this->carregarOrcamentosDetalhados();
        } elseif ($tab === 'visao-geral' && empty($this->timelineEventos)) {
            $this->carregarTimelineEventos();
        }
    }

    public function calcularKPIs()
    {
        // KPIs originais
        $this->totalEmProjetos = Orcamento::where('cliente_id', $this->cliente->id)
                                          ->where('status', 'Aprovado')
                                          ->sum('valor_total');

        $this->totalPago = Pagamento::whereHas('orcamento', function ($query) {
                                        $query->where('cliente_id', $this->cliente->id);
                                    })->sum('valor_pago');

        $this->saldoDevedor = $this->totalEmProjetos - $this->totalPago;

        // Novos KPIs
        $this->orcamentosEnviados = Orcamento::where('cliente_id', $this->cliente->id)->count();
        
        $orcamentosAprovados = Orcamento::where('cliente_id', $this->cliente->id)
                                       ->where('status', 'Aprovado')
                                       ->count();
        
        $this->taxaAprovacao = $this->orcamentosEnviados > 0 
            ? ($orcamentosAprovados / $this->orcamentosEnviados) * 100 
            : 0;

        $this->totalAReceber = $this->saldoDevedor; // Mesmo que saldo devedor por enquanto
    }

    public function carregarDados()
    {
        // Carrega dados para a aba de pagamentos (funcionalidade original)
        $this->orcamentos = Orcamento::where('cliente_id', $this->cliente->id)
                                     ->where('status', 'Aprovado')
                                     ->whereBetween('updated_at', [$this->dataInicio, $this->dataFim])
                                     ->withSum('pagamentos', 'valor_pago')
                                     ->orderBy('updated_at', 'desc')
                                     ->get();

        $this->pagamentos = Pagamento::whereHas('orcamento', function ($query) {
                                        $query->where('cliente_id', $this->cliente->id);
                                    })
                                    ->whereBetween('data_pagamento', [$this->dataInicio, $this->dataFim])
                                    ->orderBy('data_pagamento', 'desc')
                                    ->get();
    }

    public function carregarOrcamentosDetalhados()
    {
        $query = Orcamento::where('cliente_id', $this->cliente->id)
                          ->with(['pagamentos', 'autor']);

        // Aplicar filtro de status
        if ($this->orcamentoStatusFilter !== 'todos') {
            $query->where('status', $this->orcamentoStatusFilter);
        }

        // Aplicar ordenação
        $query->orderBy($this->orcamentoOrderBy, $this->orcamentoOrderDirection);

        $this->orcamentosDetalhados = $query->get();
    }

    public function setOrcamentoStatusFilter($status)
    {
        $this->orcamentoStatusFilter = $status;
        $this->carregarOrcamentosDetalhados();
    }

    public function setOrcamentoOrder($field, $direction = 'asc')
    {
        $this->orcamentoOrderBy = $field;
        $this->orcamentoOrderDirection = $direction;
        $this->carregarOrcamentosDetalhados();
    }

    public function getStatusOptions()
    {
        return [
            'todos' => 'Todos',
            'Analisando' => 'Analisando',
            'Aprovado' => 'Aprovado',
            'Rejeitado' => 'Rejeitado',
            'Finalizado' => 'Finalizado',
            'Pago' => 'Pago'
        ];
    }

    public function carregarTimelineEventos()
    {
        $eventos = collect();

        // Adiciona orçamentos criados
        $orcamentos = Orcamento::where('cliente_id', $this->cliente->id)
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        foreach ($orcamentos as $orcamento) {
            $eventos->push([
                'tipo' => 'orcamento_criado',
                'data' => $orcamento->created_at,
                'titulo' => 'Orçamento Criado',
                'descricao' => "Orçamento #{$orcamento->numero} - {$orcamento->titulo}",
                'status' => $orcamento->status,
                'valor' => $orcamento->valor_total,
                'icone' => 'document-text',
                'cor' => 'blue'
            ]);

            // Se foi aprovado, adiciona evento de aprovação
            if ($orcamento->status === 'Aprovado') {
                $eventos->push([
                    'tipo' => 'orcamento_aprovado',
                    'data' => $orcamento->updated_at,
                    'titulo' => 'Orçamento Aprovado',
                    'descricao' => "Orçamento #{$orcamento->numero} foi aprovado",
                    'status' => 'Aprovado',
                    'valor' => $orcamento->valor_total,
                    'icone' => 'check-circle',
                    'cor' => 'green'
                ]);
            }
        }

        // Adiciona pagamentos
        $pagamentos = Pagamento::whereHas('orcamento', function ($query) {
                                    $query->where('cliente_id', $this->cliente->id);
                                })
                                ->with('orcamento')
                                ->orderBy('data_pagamento', 'desc')
                                ->get();

        foreach ($pagamentos as $pagamento) {
            $eventos->push([
                'tipo' => 'pagamento_recebido',
                'data' => $pagamento->data_pagamento,
                'titulo' => 'Pagamento Recebido',
                'descricao' => "Pagamento de R$ " . number_format($pagamento->valor_pago, 2, ',', '.') . " ref. Orçamento #{$pagamento->orcamento->numero}",
                'status' => 'Pago',
                'valor' => $pagamento->valor_pago,
                'icone' => 'currency-dollar',
                'cor' => 'green'
            ]);
        }

        // Ordena por data decrescente
        $this->timelineEventos = $eventos->sortByDesc('data')->take(20)->values();
    }

    // Método para compatibilidade com a funcionalidade original
    public function calcularExtrato()
    {
        $this->calcularKPIs();
        $this->carregarDados();
    }

    public function gerarLinkPublicoExtrato()
    {
        // Gera token se não existir
        if (empty($this->cliente->token)) {
            $this->cliente->token = \Illuminate\Support\Str::uuid();
            $this->cliente->save();
        }

        // Retorna o link público
        return $this->cliente->public_extrato_url;
    }

    public function compartilharExtrato()
    {
        $linkPublico = $this->gerarLinkPublicoExtrato();
        
        // Emite evento JavaScript para copiar o link
        $this->dispatch('link-copiado', [
            'link' => $linkPublico,
            'message' => 'Link do extrato copiado para a área de transferência!'
        ]);
    }

    public function enviarMensagem()
    {
        // Emite evento JavaScript para abrir o modal de envio de mensagem
        $this->dispatch('abrir-modal-mensagem', [
            'cliente' => [
                'nome' => $this->cliente->name,
                'email' => $this->cliente->email,
                'telefone' => $this->cliente->phone,
                'whatsapp' => $this->cliente->phone
            ]
        ]);
    }

    public function alternarExtratoAtivo()
    {
        $this->cliente->extrato_ativo = !$this->cliente->extrato_ativo;
        $this->cliente->save();
        
        $status = $this->cliente->extrato_ativo ? 'ativado' : 'desativado';
        
        // Emite evento de notificação
        $this->dispatch('notificacao', [
            'tipo' => 'success',
            'message' => "Extrato público {$status} com sucesso!"
        ]);
    }

    public function render()
    {
        return view('livewire.cliente.cliente-360');
    }
}