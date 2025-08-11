<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Pagamento;
use Livewire\Component;

class ClienteDetalhes extends Component
{
    public Cliente $cliente;

    // Propriedades para os KPIs
    public float $totalEmProjetos = 0;
    public float $totalPago = 0;
    public float $saldoDevedor = 0;

    // Propriedades para o filtro de data
    public $dataInicio;
    public $dataFim;

    // Coleções para as tabelas
    public $orcamentos;
    public $pagamentos;

    public function mount()
    {
        $this->dataFim = now()->format('Y-m-d');
        $this->dataInicio = now()->subYear()->format('Y-m-d');
        $this->calcularExtrato();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dataInicio', 'dataFim'])) {
            $this->calcularExtrato();
        }
    }

    public function calcularExtrato()
    {
        // Calcula os KPIs GERAIS
        $this->totalEmProjetos = Orcamento::where('cliente_id', $this->cliente->id)
                                          ->where('status', 'Aprovado')
                                          ->sum('valor_total');

        $this->totalPago = Pagamento::whereHas('orcamento', function ($query) {
                                        $query->where('cliente_id', $this->cliente->id);
                                    })->sum('valor_pago');

        $this->saldoDevedor = $this->totalEmProjetos - $this->totalPago;

        // ATUALIZAÇÃO: Usamos withSum() para carregar o total pago de CADA orçamento
        $this->orcamentos = Orcamento::where('cliente_id', $this->cliente->id)
                                     ->where('status', 'Aprovado')
                                     ->whereBetween('updated_at', [$this->dataInicio, $this->dataFim])
                                     ->withSum('pagamentos', 'valor_pago') // Carrega a soma dos pagamentos para cada orçamento
                                     ->orderBy('updated_at', 'desc')
                                     ->get();

        // Busca os pagamentos (créditos) DENTRO do período do filtro
        $this->pagamentos = Pagamento::whereHas('orcamento', function ($query) {
                                        $query->where('cliente_id', $this->cliente->id);
                                    })
                                    ->whereBetween('data_pagamento', [$this->dataInicio, $this->dataFim])
                                    ->orderBy('data_pagamento', 'desc')
                                    ->get();
    }

    public function render()
    {
        return view('livewire.cliente.cliente-detalhes');
    }
}
