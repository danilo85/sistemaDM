<?php

namespace App\Livewire\Dashboard;

use App\Models\Orcamento;
use App\Models\Transacao;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardStats extends Component
{
    public function render()
    {
        $userId = Auth::id();
        $now = now();

        // KPIs Financeiros
        $faturamentoMes = Transacao::where('user_id', $userId)
            ->where('tipo', 'receita')
            ->whereMonth('data', $now->month)
            ->whereYear('data', $now->year)
            ->sum('valor');

        $despesasMes = Transacao::where('user_id', $userId)
            ->where('tipo', 'despesa')
            ->whereMonth('data', $now->month)
            ->whereYear('data', $now->year)
            ->sum('valor');
            
        $saldoMes = $faturamentoMes - $despesasMes;

        $totalAReceber = Transacao::where('user_id', $userId)
            ->where('tipo', 'receita')
            ->where('pago', false)
            ->sum('valor');

        // KPIs de Projetos
        $orcamentosPendentes = Orcamento::where('user_id', $userId)
            ->where('status', 'Analisando')
            ->count();
            
        $projetosEmAndamento = Orcamento::where('user_id', $userId)
            ->where('status', 'Aprovado')
            ->count();

        // Atividades Recentes
        // CORREÇÃO: Adicionado 'with('cliente')' para otimizar a consulta
        $orcamentosRecentes = Orcamento::with('cliente')->where('user_id', $userId)->latest()->take(5)->get();
        $transacoesRecentes = Transacao::where('user_id', $userId)->latest()->take(5)->get();

        return view('livewire.dashboard.dashboard-stats', [
            'faturamentoMes' => $faturamentoMes,
            'despesasMes' => $despesasMes,
            'saldoMes' => $saldoMes,
            'totalAReceber' => $totalAReceber,
            'orcamentosPendentes' => $orcamentosPendentes,
            'projetosEmAndamento' => $projetosEmAndamento,
            'orcamentosRecentes' => $orcamentosRecentes,
            'transacoesRecentes' => $transacoesRecentes,
        ]);
    }
}