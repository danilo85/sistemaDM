<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Cliente;
use App\Models\Orcamento;
use Barryvdh\DomPDF\Facade\Pdf; // <-- 1. Importa a ferramenta de PDF
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Receipt;
use Illuminate\Http\Response; // <-- Adicionado para o tipo de retorno do PDF
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // <-- 2. Importa a ferramenta de manipulação de texto
use Illuminate\View\View;


class OrcamentoController extends Controller
{
    /**
     * Exibe uma lista de todos os orçamentos.
     */
    public function index(): View
    {
            return view('admin.orcamentos.index');

    }

    public function create(): View
    {
        // A única responsabilidade dele é mostrar a "casca"
        return view('admin.orcamentos.create');
    }

    /**
     * Salva um novo orçamento no banco de dados.
     */
    /**
     * Salva um novo orçamento no banco de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. LIMPEZA E PREPARAÇÃO DOS DADOS
        $request->merge([
            'valor_total' => str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $request->valor_total ?? '0'))
        ]);
        if ($request->filled('data_emissao')) {
            $request->merge(['data_emissao' => Carbon::createFromFormat('d/m/Y', $request->data_emissao)->format('Y-m-d')]);
        }
        if ($request->filled('data_validade')) {
            $request->merge(['data_validade' => Carbon::createFromFormat('d/m/Y', $request->data_validade)->format('Y-m-d')]);
        }

        // 2. VALIDAÇÃO
        $validated = $request->validate([
            'cliente_id' => 'required',
            'autores' => 'required|array',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor_total' => 'required|numeric',
            'prazo_entrega_dias' => 'nullable|integer',
            'data_emissao' => 'required|date',
            'data_validade' => 'required|date|after_or_equal:data_emissao',
            'condicoes_pagamento' => 'nullable|string',
        ]);
        
        // 3. LÓGICA DE CLIENTE (EXISTENTE OU NOVO)
        $clienteId = $validated['cliente_id'];
        if (!is_numeric($clienteId)) {
            $novoCliente = Cliente::create(['name' => $clienteId, 'is_complete' => false]);
            $clienteId = $novoCliente->id;
        }

        // 4. LÓGICA DE AUTORES (EXISTENTES E NOVOS)
        $autorIds = [];
        foreach ($validated['autores'] as $autorInput) {
            if (is_numeric($autorInput)) {
                $autorIds[] = $autorInput;
            } else {
                $novoAutor = Autor::create(['name' => $autorInput, 'is_complete' => false]);
                $autorIds[] = $novoAutor->id;
            }
        }

        // 5. CRIAÇÃO DO ORÇAMENTO
        $orcamento = Orcamento::create([
            'cliente_id' => $clienteId,
            'user_id' => Auth::id(), // <-- MUDANÇA AQUI
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'valor_total' => $validated['valor_total'],
            'prazo_entrega_dias' => $validated['prazo_entrega_dias'],
            'data_emissao' => $validated['data_emissao'],
            'data_validade' => $validated['data_validade'],
            'condicoes_pagamento' => $validated['condicoes_pagamento'],
            'status' => 'Analisando',
            'numero' => (Orcamento::max('numero') ?? 0) + 1,
        ]);

        // 6. CONECTA OS AUTORES AO ORÇAMENTO NA TABELA PIVÔ
        $orcamento->autores()->attach($autorIds);

        // 7. REDIRECIONA COM SUCESSO
        return redirect()->route('orcamentos.index')
                         ->with('success', 'Orçamento Nº ' . $orcamento->numero . ' criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um orçamento específico.
     */
    public function show(Orcamento $orcamento): View
    {
        // Carrega os relacionamentos que já tínhamos
        $orcamento->load('cliente', 'autores');

        // Busca as atividades/histórico para este orçamento específico,
        // ordenando da mais recente para a mais antiga.
        $atividades = $orcamento->activities()->latest()->get();

        // Retorna a view, passando tanto o orçamento quanto o seu histórico
        return view('admin.orcamentos.show', [
            'orcamento' => $orcamento,
            'atividades' => $atividades, // <-- A parte crucial é garantir que esta linha existe
        ]);
    }

    /**
     * Mostra o formulário para editar um orçamento existente.
     */
    public function edit(Orcamento $orcamento): View
    {
        return view('admin.orcamentos.edit', ['orcamento' => $orcamento]);
    }

    /**
     * Atualiza um orçamento existente no banco de dados.
     */
    public function update(Request $request, Orcamento $orcamento): RedirectResponse
    {
        
        if ($request->data_emissao) {
            $request->merge(['data_emissao' => Carbon::createFromFormat('d/m/Y', $request->data_emissao)->format('Y-m-d')]);
        }
        if ($request->data_validade) {
            $request->merge(['data_validade' => Carbon::createFromFormat('d/m/Y', $request->data_validade)->format('Y-m-d')]);
        }

        // 2. VALIDAÇÃO
        $validated = $request->validate([
            'cliente_id' => 'required',
            'autores' => 'required|array',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor_total' => 'required|numeric',
            'prazo_entrega_dias' => 'nullable|integer',
            'data_emissao' => 'required|date',
            'data_validade' => 'required|date|after_or_equal:data_emissao',
            'condicoes_pagamento' => 'nullable|string',
        ]);

        // 3. LÓGICA DE CLIENTE (EXISTENTE OU NOVO)
        $clienteId = $validated['cliente_id'];
        if (!is_numeric($clienteId)) {
            $novoCliente = Cliente::create(['name' => $clienteId, 'is_complete' => false]);
            $clienteId = $novoCliente->id;
        }

        // 4. LÓGICA DE AUTORES (EXISTENTES E NOVOS)
        $autorIds = [];
        foreach ($validated['autores'] as $autorInput) {
            if (is_numeric($autorInput)) {
                $autorIds[] = $autorInput;
            } else {
                $novoAutor = Autor::create(['name' => $autorInput, 'is_complete' => false]);
                $autorIds[] = $novoAutor->id;
            }
        }

        // 5. ATUALIZAÇÃO DO ORÇAMENTO
        // (Note que usamos o $validated para a maioria dos campos,
        // mas o cliente_id vem da nossa lógica acima)
        $orcamento->update([
            'cliente_id' => $clienteId,
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'valor_total' => $validated['valor_total'],
            'prazo_entrega_dias' => $validated['prazo_entrega_dias'],
            'data_emissao' => $validated['data_emissao'],
            'data_validade' => $validated['data_validade'],
            'condicoes_pagamento' => $validated['condicoes_pagamento'],
        ]);

        // 6. SINCRONIZA OS AUTORES NA TABELA PIVÔ
        // sync() é inteligente: remove os antigos que não estão na lista e adiciona os novos.
        $orcamento->autores()->sync($autorIds);

        // 7. REDIRECIONA COM SUCESSO
        return redirect()->route('orcamentos.index')
                        ->with('success', 'Orçamento Nº ' . $orcamento->numero . ' atualizado com sucesso!');
    }

    /**
     * Remove um orçamento específico do banco de dados.
     */
    public function destroy(Orcamento $orcamento): RedirectResponse
    {
        // Deleta o orçamento do banco
        $orcamento->delete();

        // Redireciona de volta para a lista com uma mensagem de sucesso
        return redirect()->route('orcamentos.index')
                        ->with('success', 'Orçamento excluído com sucesso!');
    }

   /**
     * Exibe a versão pública de um orçamento para o cliente.
     */
    public function publicShow(string $token): View
    {
        $orcamento = Orcamento::where('token', $token)->firstOrFail();
        $orcamento->load('cliente', 'autores');

        // Passamos o número de parcelas para a view, o padrão é 2 se não especificado
        $parcelas = request()->query('parcelas', 2);

        return view('admin.orcamentos.public-show', [
            'orcamento' => $orcamento,
            'parcelas' => $parcelas,
        ]);
    }

    /**
     * Cliente aprova o orçamento.
     */
    public function approve(string $token)
    {
        $orcamento = Orcamento::where('token', $token)->firstOrFail();

        // Guarda o status anterior na sessão antes de mudar
        session()->put('previous_status_' . $orcamento->id, $orcamento->status);

        $orcamento->status = 'Aprovado';
        $orcamento->save();

        // Usamos uma chave de sessão especial para o toast com "Desfazer"
        return redirect()->route('orcamentos.public.show', $orcamento->token)
                        ->with('undo_action', 'Orçamento aprovado!');
    }
    /**
     * Reverte o status de um orçamento para o estado anterior.
     */
    public function revertStatus(string $token)
    {
        $orcamento = Orcamento::where('token', $token)->firstOrFail();

        // Pega o status anterior que guardamos na sessão e o remove
        $statusAnterior = session()->pull('previous_status_' . $orcamento->id, 'Analisando');

        $orcamento->status = $statusAnterior;
        $orcamento->save();

        return redirect()->route('orcamentos.public.show', $orcamento->token)
                        ->with('success', 'Ação desfeita com sucesso.'); // Agora usa o toast normal de sucesso
    }
    /**
     * Cliente rejeita o orçamento.
     */
    public function reject(string $token)
    {
        $orcamento = Orcamento::where('token', $token)->firstOrFail();

        // Guarda o status anterior na sessão antes de mudar
        session()->put('previous_status_' . $orcamento->id, $orcamento->status);

        $orcamento->status = 'Rejeitado';
        $orcamento->save();

        return redirect()->route('orcamentos.public.show', $orcamento->token)
                        ->with('undo_action', 'Orçamento rejeitado.');
    }
    public function showPublic($token)
        {
            // CORREÇÃO: Usamos with() para carregar os relacionamentos de uma vez.
            // Isso garante que $orcamento->user não será nulo.
            $orcamento = Orcamento::where('token', $token)
                                ->with('user', 'cliente') // Garante que o usuário e o cliente sejam carregados
                                ->firstOrFail();

            // Lógica para determinar o número de parcelas (ajuste se necessário)
            $parcelas = str_contains(strtolower($orcamento->condicoes_pagamento), '40%') ? 2 : 1;

            return view('orcamentos.public-show', [
                'orcamento' => $orcamento,
                'parcelas' => $parcelas, // Garante que a variável $parcelas seja passada para a view
            ]);
        }

 /**
     * Mostra a página pública de um recibo.
     */
    public function showPublicReceipt($token)
    {
        // Encontra o recibo pelo token ou falha com um erro 404 (não encontrado)
        $receipt = Receipt::where('token', $token)
                          ->with('pagamento.orcamento.user', 'pagamento.orcamento.cliente')
                          ->firstOrFail();

        // Retorna a view do recibo, passando os dados encontrados
        // (Nós criaremos o arquivo 'receipts.public-show' no próximo passo)
        return view('receipts.public-show', [
            'receipt' => $receipt
        ]);
    }

}