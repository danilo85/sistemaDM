<?php

namespace App\Livewire;

use App\Models\Banco;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BancoForm extends Component
{
    public ?Banco $banco;

    // Propriedades do formulário
    public string $nome = '';
    public string $tipo_conta = 'Corrente';
    public $saldo_inicial = '0';

    public $bancosDisponiveis;

    public function mount(array $bancosDisponiveis, Banco $banco = null)
    {
        $this->banco = $banco ?? new Banco();
        $this->bancosDisponiveis = collect($bancosDisponiveis);

        // Preenche o formulário se estiver editando
        if ($this->banco->exists) {
            $this->nome = $this->banco->nome;
            $this->tipo_conta = $this->banco->tipo_conta;
            $this->saldo_inicial = $this->banco->saldo_inicial;
        }
    }

    public function save()
    {
        $valorLimpo = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $this->saldo_inicial ?? '0'));

        $validated = $this->validate([
            'nome' => 'required|string|max:255',
            'tipo_conta' => 'required|string|max:255',
            'saldo_inicial' => 'required',
        ]);

        $dataToSave = [
            'user_id' => Auth::id(),
            'nome' => $validated['nome'],
            'tipo_conta' => $validated['tipo_conta'],
            'saldo_inicial' => $valorLimpo,
        ];

        if ($this->banco->exists) {
            $this->banco->update($dataToSave);
            session()->flash('success', 'Conta bancária atualizada com sucesso!');
        } else {
            Banco::create($dataToSave);
            session()->flash('success', 'Conta bancária salva com sucesso!');
        }

        return redirect()->route('bancos.index');
    }

    public function render()
    {
        return view('livewire.banco-form');
    }
}
