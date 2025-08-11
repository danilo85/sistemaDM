<?php

namespace App\Livewire;

use App\Models\CartaoCredito;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartaoForm extends Component
{
    public ?CartaoCredito $cartao;

    // Propriedades do formulário
    public string $nome = '';
    public string $bandeira = '';
    public $limite_credito = '0';
    public ?int $dia_fechamento = null;
    public ?int $dia_vencimento = null;

    public $bandeirasDisponiveis;

    public function mount(array $bandeirasDisponiveis, CartaoCredito $cartao = null)
    {
        $this->cartao = $cartao ?? new CartaoCredito();
        $this->bandeirasDisponiveis = collect($bandeirasDisponiveis);

        // Preenche o formulário se estiver editando
        if ($this->cartao->exists) {
            $this->nome = $this->cartao->nome;
            $this->bandeira = $this->cartao->bandeira;
            $this->limite_credito = $this->cartao->limite_credito;
            $this->dia_fechamento = $this->cartao->dia_fechamento;
            $this->dia_vencimento = $this->cartao->dia_vencimento;
        }
    }

    public function save()
    {
        $valorLimpo = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $this->limite_credito ?? '0'));

        $validated = $this->validate([
            'nome' => 'required|string|max:255',
            'bandeira' => 'required|string|max:255',
            'limite_credito' => 'required',
            'dia_fechamento' => 'required|integer|min:1|max:31',
            'dia_vencimento' => 'required|integer|min:1|max:31',
        ]);

        $dataToSave = [
            'user_id' => Auth::id(),
            'nome' => $validated['nome'],
            'bandeira' => $validated['bandeira'],
            'limite_credito' => $valorLimpo,
            'dia_fechamento' => $validated['dia_fechamento'],
            'dia_vencimento' => $validated['dia_vencimento'],
        ];

        if ($this->cartao->exists) {
            $this->cartao->update($dataToSave);
            session()->flash('success', 'Cartão atualizado com sucesso!');
        } else {
            CartaoCredito::create($dataToSave);
            session()->flash('success', 'Cartão salvo com sucesso!');
        }

        return redirect()->route('cartoes.index');
    }

    public function render()
    {
        return view('livewire.cartao-form');
    }
}
