<?php

namespace App\Livewire;

use App\Models\Autor;
use App\Models\Cliente;
use App\Models\Orcamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrcamentoEditForm extends Component
{
    public Orcamento $orcamento;

    // Propriedades para os campos do formulário
    public $cliente_id;
    public $autores = [];
    public $titulo;
    public $descricao;
    public $valor_total;
    public $prazo_entrega_dias;
    public $data_emissao;
    public $data_validade;
    public $condicoes_pagamento;

    public function mount(Orcamento $orcamento)
    {
        $this->orcamento = $orcamento;
        
        // Carrega os dados do orçamento existente para o formulário
        $this->cliente_id = $orcamento->cliente_id;
        $this->autores = $orcamento->autores->pluck('id')->toArray();
        $this->titulo = $orcamento->titulo;
        $this->descricao = $orcamento->descricao;
        $this->valor_total = number_format($orcamento->valor_total, 2, ',', '.');
        $this->prazo_entrega_dias = $orcamento->prazo_entrega_dias;
        $this->data_emissao = $orcamento->data_emissao->format('d/m/Y');
        $this->data_validade = $orcamento->data_validade->format('d/m/Y');
        $this->condicoes_pagamento = $orcamento->condicoes_pagamento;
    }

    public function update()
    {
        // 1. PRÉ-PROCESSAMENTO: Cria novos clientes/autores se necessário
        if (!is_numeric($this->cliente_id)) {
            $novoCliente = Cliente::create([
                'name' => $this->cliente_id, 
                'is_complete' => false,
                'user_id' => Auth::id()
            ]);
            $this->cliente_id = $novoCliente->id;
        }

        $autorIds = [];
        foreach ($this->autores as $autorInput) {
            if (is_numeric($autorInput)) {
                $autorIds[] = (int)$autorInput;
            } else {
                $novoAutor = Autor::create([
                    'name' => $autorInput, 
                    'is_complete' => false,
                    'user_id' => Auth::id()
                ]);
                $autorIds[] = $novoAutor->id;
            }
        }
        $this->autores = $autorIds;

        // 2. LIMPEZA E VALIDAÇÃO
        $valorLimpo = str_replace(['.', ','], ['', '.'], $this->valor_total ?? '0');
        
        $validated = $this->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'autores' => 'required|array|min:1',
            'autores.*' => 'exists:autores,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'prazo_entrega_dias' => 'nullable|integer',
            'data_emissao' => 'required|date_format:d/m/Y',
            'data_validade' => 'required|date_format:d/m/Y',
            'condicoes_pagamento' => 'nullable|string',
        ]);

        // 3. SALVAMENTO
        DB::transaction(function () use ($validated, $valorLimpo) {
            $this->orcamento->update([
                'cliente_id' => $validated['cliente_id'],
                'titulo' => $validated['titulo'],
                'descricao' => $validated['descricao'],
                'valor_total' => $valorLimpo,
                'prazo_entrega_dias' => $validated['prazo_entrega_dias'],
                'data_emissao' => Carbon::createFromFormat('d/m/Y', $validated['data_emissao'])->format('Y-m-d'),
                'data_validade' => Carbon::createFromFormat('d/m/Y', $validated['data_validade'])->format('Y-m-d'),
                'condicoes_pagamento' => $validated['condicoes_pagamento'],
            ]);

            $this->orcamento->autores()->sync($validated['autores']);
        });

        return redirect()->route('orcamentos.show', $this->orcamento)
                         ->with('success', 'Orçamento Nº ' . $this->orcamento->numero . ' atualizado com sucesso!');
    }

    public function render()
    {
        // CORREÇÃO: Carregamos a lista completa de opções aqui para garantir que esteja sempre atualizada
        $clientes = Cliente::where('user_id', Auth::id())->orderBy('name')->get();
        $autoresDisponiveis = Autor::where('user_id', Auth::id())->orderBy('name')->get();

        return view('livewire.orcamento-edit-form', [
            'clientes' => $clientes,
            'autoresDisponiveis' => $autoresDisponiveis,
        ]);
    }
}
