<?php

namespace App\Http\Controllers;

use App\Models\CartaoCredito;
use App\Traits\FormatsDates; 
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartaoCreditoController extends Controller
{
    use FormatsDates; // <-- Ativa o "superpoder" de traduzir datas

    /**
     * Exibe a lista de cartões de crédito.
     */
    public function index(): View
    {
         // CORREÇÃO: Buscamos apenas os cartões do usuário logado
        $cartoes = CartaoCredito::where('user_id', Auth::id())->orderBy('nome')->get();
        return view('admin.cartoes.index', ['cartoes' => $cartoes]);
    }

    /**
     * Mostra o formulário para criar um novo cartão.
     */
    public function create(): View
    {
        $bandeirasDisponiveis = ['Visa', 'Mastercard', 'Elo', 'American Express', 'Hipercard', 'Outra'];
        return view('admin.cartoes.create', ['bandeirasDisponiveis' => $bandeirasDisponiveis]);
    }

    /**
     * Salva um novo cartão no banco de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        // Limpa a máscara de moeda antes de validar
        $request->merge([
            'limite_credito' => str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $request->limite_credito ?? '0'))
        ]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'bandeira' => 'required|string|max:255',
            'limite_credito' => 'required|numeric|min:0',
            'dia_fechamento' => 'required|integer|min:1|max:31',
            'dia_vencimento' => 'required|integer|min:1|max:31',
        ]);

        // CORREÇÃO: Adiciona o user_id aos dados validados
        $validated['user_id'] = Auth::id();

        CartaoCredito::create($validated);

        return redirect()->route('cartoes.index')->with('success', 'Cartão de crédito cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um cartão existente.
     */
    public function edit(CartaoCredito $cartao): View
    {
        $bandeirasDisponiveis = ['Visa', 'Mastercard', 'Elo', 'American Express', 'Hipercard', 'Outra'];

        return view('admin.cartoes.edit', [
            'cartao' => $cartao,
            'bandeirasDisponiveis' => $bandeirasDisponiveis,
        ]);
    }

    /**
     * Atualiza um cartão existente no banco de dados.
     */
    public function update(Request $request, CartaoCredito $cartao): RedirectResponse
    {
        // Limpa a máscara de moeda antes de validar
        $request->merge([
            'limite_credito' => str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $request->limite_credito ?? '0'))
        ]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'bandeira' => 'required|string|max:255',
            'limite_credito' => 'required|numeric|min:0',
            'dia_fechamento' => 'required|integer|min:1|max:31',
            'dia_vencimento' => 'required|integer|min:1|max:31',
        ]);

        $cartao->update($validated);

        return redirect()->route('cartoes.index')->with('success', 'Cartão de crédito atualizado com sucesso!');
    }

    /**
     * Remove um cartão de crédito do banco de dados.
     */
    public function destroy(CartaoCredito $cartao): RedirectResponse
    {
        $cartao->delete();
        return redirect()->route('cartoes.index')->with('success', 'Cartão de crédito excluído com sucesso!');
    }

    /**
     * Exibe o extrato de um cartão de crédito para um mês específico.
     */
    public function extrato(Request $request, CartaoCredito $cartao): View
    {
        $request->validate([
            'ano' => 'sometimes|integer',
            'mes' => 'sometimes|integer',
        ]);

        $ano = $request->input('ano', now()->year);
        $mes = $request->input('mes', now()->month);
        $dataAtual = Carbon::createFromDate($ano, $mes, 1);

        $transacoes = $cartao->transacoes()
                             ->where('tipo', 'despesa')
                             ->whereYear('data', $ano)
                             ->whereMonth('data', $mes)
                             ->with('categoria')
                             ->latest('data')
                             ->get();
        
        $totalFatura = $transacoes->sum('valor');
        
        // --- INÍCIO DA MUDANÇA ---
        // Usamos nosso Trait para gerar os textos traduzidos
        $tituloDoMesCompleto = $this->getMesAnoTraduzido($dataAtual);
        $nomeDoMes = $this->getMesTraduzido($dataAtual->month);

        return view('admin.cartoes.extrato', [
            'cartao' => $cartao,
            'transacoes' => $transacoes,
            'dataAtual' => $dataAtual,
            'totalFatura' => $totalFatura,
            'tituloDoMesCompleto' => $tituloDoMesCompleto, // Nome completo para o título principal
            'nomeDoMes' => $nomeDoMes, // Apenas o nome do mês para o card
        ]);
        // --- FIM DA MUDANÇA ---
    }
}