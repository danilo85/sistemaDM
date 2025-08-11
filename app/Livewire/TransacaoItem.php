<?php

namespace App\Livewire;

use App\Models\Transacao;
use Livewire\Component;
use Livewire\Attributes\On;

class TransacaoItem extends Component
{
    public Transacao $transacao;
    public $isDetalheFatura = false;
    public bool $showConfirmModal = false;

    #[On('faturaPaga')]
    public function refresh()
    {
        $this->transacao->refresh();
    }

    public function togglePago()
    {
        $this->transacao->pago = !$this->transacao->pago;
        $this->transacao->save();
    }

    // --- MÉTODO DUPLICATE RESTAURADO ---
    public function duplicate()
    {
        // A única responsabilidade deste método agora é redirecionar para a rota de criação,
        // passando o ID do item original como um parâmetro na URL.
        return redirect()->route('transacoes.create', ['from' => $this->transacao->id]);
    }

    // --- MÉTODOS DE EXCLUSÃO CORRIGIDOS PARA USAR RELOAD ---
    public function confirmDelete()
    {
        $this->showConfirmModal = true;
    }

    public function deleteSingle()
    {
        $this->transacao->delete();
        session()->flash('success', 'Lançamento excluído com sucesso!');
        return $this->redirect(request()->header('Referer'));
    }

    public function deleteAll()
    {
        if ($this->transacao->compra_id) {
            Transacao::where('compra_id', $this->transacao->compra_id)->delete();
            session()->flash('success', 'Todas as parcelas foram excluídas com sucesso!');
        } else {
            $this->transacao->delete();
            session()->flash('success', 'Lançamento excluído com sucesso!');
        }
        return $this->redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.transacao-item');
    }
}