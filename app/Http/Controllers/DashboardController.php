<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\Transacao;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $hoje = Carbon::now();

        // Preparamos os dados padrão para TODOS os usuários
        $viewData = [
            'diasComTransacoes' => collect(), // Começa como uma coleção vazia
            'anoAtual' => $hoje->year,
            'mesAtual' => $hoje->month,
            'totalUsers' => 0,
            'latestUsers' => collect(),
            'orcamentosAnalisando' => 0,
            'orcamentosAprovados' => 0,
            'receitasDoMes' => 0,
            'despesasDoMes' => 0,
        ];

        // Se o usuário for admin, preenchemos com os dados reais
        if ($user->role === 'admin') {
            $viewData['totalUsers'] = User::count();
            $viewData['latestUsers'] = User::latest()->take(5)->get();
            
            $transacoesDoMes = Transacao::whereYear('data', $hoje->year)
                                        ->whereMonth('data', $hoje->month)
                                        ->get();
            
            $viewData['diasComTransacoes'] = $transacoesDoMes->groupBy(function($transacao) {
                return Carbon::parse($transacao->data)->format('j');
            });
            
            $viewData['orcamentosAnalisando'] = Orcamento::where('status', 'Analisando')->count();
            $viewData['orcamentosAprovados'] = Orcamento::where('status', 'Aprovado')->count();
            $viewData['receitasDoMes'] = $transacoesDoMes->where('tipo', 'receita')->where('pago', true)->sum('valor');
            $viewData['despesasDoMes'] = $transacoesDoMes->where('tipo', 'despesa')->where('pago', true)->sum('valor');
        }

        return view('dashboard', $viewData);
    }
}