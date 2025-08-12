<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Orcamento;
use App\Models\Banco;
use App\Models\CartaoCredito;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pagamentos = Pagamento::with(['orcamento.cliente', 'transacao'])
                               ->whereHas('orcamento', function ($query) {
                                   $query->where('user_id', Auth::id());
                               })
                               ->orderBy('data_pagamento', 'desc')
                               ->paginate(15);

        return view('admin.pagamentos.index', compact('pagamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $orcamento = null;
        
        // Se foi passado um orcamento_id, carrega o orçamento
        if ($request->has('orcamento_id')) {
            $orcamento = Orcamento::with('cliente')
                                  ->where('id', $request->orcamento_id)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();
        }

        // Carrega bancos e cartões do usuário para as opções de pagamento
        $bancos = Banco::where('user_id', Auth::id())->orderBy('nome')->get();
        $cartoes = CartaoCredito::where('user_id', Auth::id())->orderBy('nome')->get();
        
        // Se não há orcamento específico, carrega todos os orçamentos aprovados
        $orcamentos = Orcamento::with('cliente')
                               ->where('user_id', Auth::id())
                               ->where('status', 'Aprovado')
                               ->orderBy('numero', 'desc')
                               ->get();

        return view('admin.pagamentos.create', [
            'orcamento' => $orcamento,
            'orcamentos' => $orcamentos,
            'bancos' => $bancos,
            'cartoes' => $cartoes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'orcamento_id' => 'required|exists:orcamentos,id',
            'valor_pago' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'tipo_pagamento' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'metodo_pagamento' => 'nullable|string|max:255'
        ]);

        // Verifica se o orçamento pertence ao usuário autenticado
        $orcamento = Orcamento::where('id', $validated['orcamento_id'])
                              ->where('user_id', Auth::id())
                              ->firstOrFail();

        // Cria o pagamento
        $pagamento = Pagamento::create([
            'orcamento_id' => $validated['orcamento_id'],
            'valor_pago' => $validated['valor_pago'],
            'data_pagamento' => $validated['data_pagamento'],
            'tipo_pagamento' => $validated['tipo_pagamento'],
            'descricao' => $validated['descricao'] ?? null,
            'metodo_pagamento' => $validated['metodo_pagamento'] ?? null
        ]);

        return redirect()->route('clientes.show', $orcamento->cliente_id)
                        ->with('success', 'Pagamento registrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pagamento $pagamento): View
    {
        // Verifica se o pagamento pertence a um orçamento do usuário
        if ($pagamento->orcamento->user_id !== Auth::id()) {
            abort(403);
        }

        $pagamento->load(['orcamento.cliente', 'transacao']);

        return view('admin.pagamentos.show', compact('pagamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagamento $pagamento): View
    {
        // Verifica se o pagamento pertence a um orçamento do usuário
        if ($pagamento->orcamento->user_id !== Auth::id()) {
            abort(403);
        }

        $pagamento->load(['orcamento.cliente']);
        $bancos = Banco::where('user_id', Auth::id())->orderBy('nome')->get();
        $cartoes = CartaoCredito::where('user_id', Auth::id())->orderBy('nome')->get();

        return view('admin.pagamentos.edit', [
            'pagamento' => $pagamento,
            'bancos' => $bancos,
            'cartoes' => $cartoes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pagamento $pagamento): RedirectResponse
    {
        // Verifica se o pagamento pertence a um orçamento do usuário
        if ($pagamento->orcamento->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'valor_pago' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'tipo_pagamento' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'metodo_pagamento' => 'nullable|string|max:255'
        ]);

        $pagamento->update($validated);

        return redirect()->route('clientes.show', $pagamento->orcamento->cliente_id)
                        ->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pagamento $pagamento): RedirectResponse
    {
        // Verifica se o pagamento pertence a um orçamento do usuário
        if ($pagamento->orcamento->user_id !== Auth::id()) {
            abort(403);
        }

        $clienteId = $pagamento->orcamento->cliente_id;
        $pagamento->delete();

        return redirect()->route('clientes.show', $clienteId)
                        ->with('success', 'Pagamento removido com sucesso!');
    }
}