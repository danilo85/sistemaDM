<?php

namespace App\Livewire;

use App\Models\Orcamento;
use App\Notifications\OrcamentoAprovadoNotification;
use Livewire\Component;

class PublicOrcamentoView extends Component
{
    public Orcamento $orcamento;
    public int $parcelas = 1;

    public function mount(Orcamento $orcamento)
    {
        $this->orcamento = $orcamento;
        
        // Define o número de parcelas baseado nas condições de pagamento
        // Se o valor for maior que R$ 1000 e as condições permitirem, divide em 2 parcelas
        if ($this->orcamento->valor_total > 1000 && 
            (stripos($this->orcamento->condicoes_pagamento, 'parcela') !== false || 
             stripos($this->orcamento->condicoes_pagamento, '40%') !== false ||
             stripos($this->orcamento->condicoes_pagamento, '60%') !== false)) {
            $this->parcelas = 2;
        }
    }

    public function aprovarOrcamento()
    {
        if ($this->orcamento->status === 'Analisando') {
            $this->orcamento->status = 'Aprovado';
            $this->orcamento->save();

            // 1. Envia a notificação para o dono do orçamento
            $this->orcamento->user->notify(new OrcamentoAprovadoNotification($this->orcamento));

            // 2. Avisa o sino para se atualizar
            $this->dispatch('new-alert-sent');

            // 3. Avisa outros componentes que o status mudou
            $this->dispatch('status-atualizado', $this->orcamento->id);

            // 4. Envia uma mensagem de sucesso para a view
            session()->flash('success', 'Orçamento aprovado com sucesso!');
        }
    }

    public function rejeitarOrcamento()
    {
        if ($this->orcamento->status === 'Analisando') {
            $this->orcamento->status = 'Rejeitado';
            $this->orcamento->save();

            // Avisa outros componentes que o status mudou
            $this->dispatch('status-atualizado', $this->orcamento->id);

            session()->flash('success', 'Orçamento rejeitado.');
        }
    }

    public function render()
    {
        // Usa o layout público (sem menu lateral e sem limitação de largura)
        return view('livewire.public-orcamento-view')->layout('layouts.public');
    }
}