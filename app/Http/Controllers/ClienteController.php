<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Pagamento; // CORREÇÃO: Importação adicionada
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
         return view('admin.clientes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:clientes,email',
            'phone' => 'nullable|string|max:25',
        ]);
        
        // CORREÇÃO: Adiciona o user_id e a flag is_complete aos dados validados
        $validated['user_id'] = Auth::id();
        $validated['is_complete'] = true;

        Cliente::create($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
       // Retorna uma nova view que vamos criar em breve,
        // passando o cliente para que o componente Livewire saiba qual carregar.
        return view('admin.clientes.show', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente): View
    {
        return view('admin.clientes.edit', ['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        // 1. Valida os dados (note a regra 'unique' ajustada para ignorar o cliente atual)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:clientes,email,' . $cliente->id,
            'phone' => 'nullable|string|max:25',
        ]);

        // 2. Adiciona a bandeirinha de "completo" aos dados validados
        $validated['is_complete'] = true;

        // 3. ATUALIZA o cliente usando o array $validated, que agora contém a nossa bandeirinha
        $cliente->update($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente deletado com sucesso!');
    }

    /**
     * Procura por clientes com base em um termo de busca.
     */
    public function search(Request $request)
    {
        $clientes = Cliente::where('name', 'LIKE', '%' . $request->query('q') . '%')
                           ->select('id', 'name')
                           ->take(10)
                           ->get();

        return response()->json($clientes);
    }
    
     /**
     * Gera e faz o download de um extrato do cliente em PDF.
     */
    public function gerarExtratoPdf(Request $request, Cliente $cliente)
        {
            // Pega as datas do filtro da URL, com valores padrão
            $dataInicio = $request->query('data_inicio', now()->subYear()->toDateString());
            $dataFim = $request->query('data_fim', now()->toDateString());

            // Busca os dados exatamente como no componente Livewire
            $totalEmProjetos = Orcamento::where('cliente_id', $cliente->id)->where('status', 'Aprovado')->sum('valor_total');
            $totalPago = Pagamento::whereHas('orcamento', fn($q) => $q->where('cliente_id', $cliente->id))->sum('valor_pago');
            $saldoDevedor = $totalEmProjetos - $totalPago;

            // ATUALIZADO: Usamos withSum() para carregar o total pago de CADA orçamento
            $orcamentos = Orcamento::where('cliente_id', $cliente->id)
                ->where('status', 'Aprovado')
                ->whereBetween('updated_at', [$dataInicio, $dataFim])
                ->withSum('pagamentos', 'valor_pago') // Carrega a soma dos pagamentos
                ->get();

            $pagamentos = Pagamento::whereHas('orcamento', fn($q) => $q->where('cliente_id', $cliente->id))
                ->whereBetween('data_pagamento', [$dataInicio, $dataFim])
                ->get();

            // Agrupa todos os dados para enviar para a view do PDF
            $dados = [
                'cliente' => $cliente,
                'dataInicio' => $dataInicio,
                'dataFim' => $dataFim,
                'totalEmProjetos' => $totalEmProjetos,
                'totalPago' => $totalPago,
                'saldoDevedor' => $saldoDevedor,
                'orcamentos' => $orcamentos,
                'pagamentos' => $pagamentos,
            ];

            // Carrega a view do PDF com os dados e gera o PDF
            $pdf = Pdf::loadView('admin.clientes.extrato-pdf', $dados);

            // Define o nome do arquivo e força o download
            return $pdf->download("extrato_{$cliente->name}_{$dataInicio}_a_{$dataFim}.pdf");
        }
}