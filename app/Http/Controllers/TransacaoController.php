<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\CartaoCredito;
use App\Models\Categoria;
use App\Models\Transacao;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importa a facade de Autenticação
use Illuminate\Support\Str;
use Illuminate\View\View;

class TransacaoController extends Controller
{
    /**
     * Exibe uma lista de todas as transações.
     */
    public function index(Request $request): View
    {
        return view('admin.transacoes.index');
    }

    /**
     * Mostra o formulário para criar uma nova transação.
     */
    public function create(Request $request): View
    {
        $transacao = new Transacao();

        if ($request->has('from')) {
            $original = Transacao::find($request->from);
            if ($original) {
                $transacao = $original->replicate();
                $transacao->pago = false; // Uma cópia nunca vem paga
                $transacao->descricao = $original->descricao . ' (Cópia)';
            }
        }

        $categorias = Categoria::where('user_id', Auth::id())->orderBy('nome')->get();
        $bancos = Banco::where('user_id', Auth::id())->orderBy('nome')->get();
        $cartoes = CartaoCredito::where('user_id', Auth::id())->orderBy('nome')->get();

        return view('admin.transacoes.create', [
            'transacao' => $transacao,
            'categorias' => $categorias,
            'bancos' => $bancos,
            'cartoes' => $cartoes,
            // CORREÇÃO: Passando ano e mês da URL para a view
            'ano' => $request->query('ano'),
            'mes' => $request->query('mes'),
        ]);
    }

    /**
     * Salva uma nova transação no banco de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        $valorLimpo = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $request->valor ?? '0'));
        $request->merge(['valor' => $valorLimpo]);
        $request->merge(['is_recurring' => $request->has('is_recurring')]);
        $request->merge(['isParcelado' => $request->has('isParcelado')]);

        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date_format:d/m/Y',
            'tipo' => 'required|string|in:receita,despesa',
            'categoria_id' => 'required|exists:categorias,id',
            'conta' => 'required|string',
            'is_recurring' => 'nullable|boolean',
            'frequency' => 'nullable|required_if:is_recurring,true|string|in:monthly,yearly',
            'isParcelado' => 'nullable|boolean',
            'parcela_total' => 'nullable|required_if:isParcelado,true|integer|min:2',
        ]);

        $dataParaSalvar = Carbon::createFromFormat('d/m/Y', $validated['data']);
        [$tipoConta, $idConta] = explode('_', $validated['conta']);
        $modeloConta = 'App\\Models\\' . $tipoConta;

        $dadosBase = [
            'user_id' => Auth::id(),
            'descricao' => $validated['descricao'],
            'data' => $dataParaSalvar->format('Y-m-d'),
            'tipo' => $validated['tipo'],
            'categoria_id' => $validated['categoria_id'],
            'transacionavel_id' => $idConta,
            'transacionavel_type' => $modeloConta,
        ];

        if ($validated['isParcelado']) {
            $valorParcela = $validated['valor'] / $validated['parcela_total'];
            $compraId = Str::uuid();

            for ($i = 1; $i <= $validated['parcela_total']; $i++) {
                Transacao::create(array_merge($dadosBase, [
                    'valor' => $valorParcela,
                    'parcela_atual' => $i,
                    'parcela_total' => $validated['parcela_total'],
                    'compra_id' => $compraId,
                    // CORREÇÃO: Usamos addMonthsNoOverflow para evitar pular meses como fevereiro
                    'data' => $dataParaSalvar->copy()->addMonthsNoOverflow($i - 1)->format('Y-m-d'),
                ]));
            }
        } elseif ($validated['is_recurring'] ?? false) {
            Transacao::create(array_merge($dadosBase, [
                'valor' => $validated['valor'],
                'recorrente' => true,
                'frequency' => $validated['frequency'],
            ]));
        } else {
            Transacao::create(array_merge($dadosBase, [
                'valor' => $validated['valor'],
            ]));
        }

        return redirect()->route('transacoes.index', [
            'ano' => $dataParaSalvar->year,
            'mes' => $dataParaSalvar->month,
        ])->with('success', 'Lançamento salvo com sucesso!');
    }

    /**
     * Mostra o formulário para editar um lançamento existente.
     */
    public function edit(Transacao $transacao): View
    {
        $categorias = Categoria::where('user_id', Auth::id())->orderBy('nome')->get();
        $bancos = Banco::where('user_id', Auth::id())->orderBy('nome')->get();
        $cartoes = CartaoCredito::where('user_id', Auth::id())->orderBy('nome')->get();

        return view('admin.transacoes.edit', [
            'transacao' => $transacao,
            'categorias' => $categorias,
            'bancos' => $bancos,
            'cartoes' => $cartoes,
        ]);
    }

    /**
     * Atualiza um lançamento existente no banco de dados.
     */
    public function update(Request $request, Transacao $transacao): RedirectResponse
    {
        // (seu código de update existente)
        return redirect()->route('transacoes.index')->with('success', 'Lançamento atualizado com sucesso!');
    }

    /**
     * Remove o lançamento especificado do banco de dados.
     */
    public function destroy(Transacao $transacao): RedirectResponse
    {
        // (seu código de destroy existente)
        return redirect()->route('transacoes.index')->with('success', 'Lançamento excluído com sucesso!');
    }
}
