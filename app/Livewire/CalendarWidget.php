<?php

namespace App\Livewire;

use App\Models\Transacao;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Computed;

class CalendarWidget extends Component
{
    public Carbon $data; // Guarda a data atual do calendário como um objeto Carbon

    // 'mount' é executado quando o componente é carregado pela primeira vez
    public function mount()
    {
        $this->data = Carbon::now()->startOfMonth();
    }

    // Método chamado pelo botão ">"
    public function proximoMes()
    {
        $this->data->addMonth();
    }

    // Método chamado pelo botão "<"
    public function mesAnterior()
    {
        $this->data->subMonth();
    }

    // Método chamado pelo botão "Hoje"
    public function mesAtual()
    {
        $this->data = Carbon::now()->startOfMonth();
    }

    // Uma propriedade computada que busca os dados sempre que a data muda
    #[Computed]
    public function transacoesDoMes()
    {
        return Transacao::whereYear('data', $this->data->year)
                            ->whereMonth('data', $this->data->month)
                            ->get();
    }

    public function render()
    {
        // Calcula os totais com base nos dados da propriedade computada
        $totalReceitas = $this->transacoesDoMes()->where('tipo', 'receita')->sum('valor');
        $totalDespesas = $this->transacoesDoMes()->where('tipo', 'despesa')->sum('valor');
        $saldoDoMes = $totalReceitas - $totalDespesas;

        // Agrupa as transações por dia para as "bolinhas"
        $diasComTransacoes = $this->transacoesDoMes()->groupBy(function($transacao) {
            return Carbon::parse($transacao->data)->format('j');
        });

        return view('livewire.calendar-widget', [
            'diasComTransacoes' => $diasComTransacoes,
            'totalReceitas' => $totalReceitas,
            'totalDespesas' => $totalDespesas,
            'saldoDoMes' => $saldoDoMes,
        ]);
    }
}