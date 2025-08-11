<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $bancos = Banco::where('user_id', Auth::id())->orderBy('nome')->get();
        return view('admin.bancos.index', ['bancos' => $bancos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bancosDisponiveis = ['Banco do Brasil', 'Bradesco', 'Caixa', 'Itaú', 'Nubank', 'Santander', 'Outro'];
        return view('admin.bancos.create', ['bancosDisponiveis' => $bancosDisponiveis]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $valorLimpo = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $request->saldo_inicial ?? '0'));
        $request->merge(['saldo_inicial' => $valorLimpo]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo_conta' => 'required|string|max:255',
            'saldo_inicial' => 'required|numeric',
        ]);

        $validated['user_id'] = Auth::id();
        Banco::create($validated);

        return redirect()->route('bancos.index')->with('success', 'Conta bancária cadastrada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banco $banco): View
    {
        // CORREÇÃO: Passamos a lista de bancos para a view de edição
        $bancosDisponiveis = ['Banco do Brasil', 'Bradesco', 'Caixa', 'Itaú', 'Nubank', 'Santander', 'Outro'];
        return view('admin.bancos.edit', [
            'banco' => $banco,
            'bancosDisponiveis' => $bancosDisponiveis
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banco $banco): RedirectResponse
    {
        $valorLimpo = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $request->saldo_inicial ?? '0'));
        $request->merge(['saldo_inicial' => $valorLimpo]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo_conta' => 'required|string|max:255',
            'saldo_inicial' => 'required|numeric',
        ]);

        $banco->update($validated);

        return redirect()->route('bancos.index')->with('success', 'Conta bancária atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banco $banco): RedirectResponse
    {
        $banco->delete();
        return redirect()->route('bancos.index')->with('success', 'Conta bancária deletada com sucesso!');
    }
}
