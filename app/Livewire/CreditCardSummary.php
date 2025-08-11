<?php

namespace App\Livewire;

use App\Models\Transacao;
use Livewire\Component;
use Livewire\Attributes\Computed;

class CreditCardSummary extends Component
{
    public $fatura;

    /**
     * Propriedade computada que verifica se a fatura está paga.
     * É uma "propriedade inteligente" que é recalculada quando necessário.
     */
    #[Computed]
    public function faturaEstaPaga()
    {
        // Garante que não haverá erro se a lista de transações estiver vazia
        if ($this->fatura->transacoes->isEmpty()) {
            return false;
        }
        // Verifica o status do primeiro item. Cast para (bool) por segurança.
        return (bool) $this->fatura->transacoes->first()->pago;
    }

    /**
     * Método chamado pelo botão para pagar ou desfazer o pagamento da fatura.
     */
    public function pagarFatura()
    {
        $transacoes = $this->fatura->transacoes;

        // Se não houver transações, não faz nada
        if ($transacoes->isEmpty()) {
            return;
        }

        // Decide qual será o novo status, usando nossa propriedade computada
        $novoStatus = !$this->faturaEstaPaga();
        $mensagem = $novoStatus ? 'Fatura marcada como paga!' : 'Pagamento da fatura desfeito!';

        $transacaoIds = $transacoes->pluck('id');
        Transacao::whereIn('id', $transacaoIds)->update(['pago' => $novoStatus]);

        // Recarrega os dados da relação para refletir a mudança na interface
        $this->fatura->transacoes = Transacao::whereIn('id', $transacaoIds)->get();

        $this->dispatch('faturaPaga', $mensagem);
    }

    public function render()
    {
        return view('livewire.credit-card-summary');
    }
}