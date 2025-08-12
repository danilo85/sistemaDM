<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Orcamento;
use Illuminate\Support\Facades\DB;

class DashboardSimplesController extends Controller
{
    public function index()
    {
        try {
            // Estatísticas básicas
            $totalClientes = Cliente::count();
            $totalOrcamentos = Orcamento::count();
            $orcamentosPendentes = Orcamento::where('status', 'pendente')->count();
            $valorTotal = Orcamento::where('status', 'aprovado')->sum('valor_total');
            
            // Orçamentos recentes (últimos 5)
            $orcamentosRecentes = Orcamento::with('cliente')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            return view('admin.dashboard-simples', compact(
                'totalClientes',
                'totalOrcamentos', 
                'orcamentosPendentes',
                'valorTotal',
                'orcamentosRecentes'
            ));
            
        } catch (\Exception $e) {
            // Em caso de erro, retorna view com valores padrão
            return view('admin.dashboard-simples', [
                'totalClientes' => 0,
                'totalOrcamentos' => 0,
                'orcamentosPendentes' => 0,
                'valorTotal' => 0,
                'orcamentosRecentes' => collect()
            ]);
        }
    }
}