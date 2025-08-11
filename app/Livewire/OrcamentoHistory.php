<?php

namespace App\Livewire;

use App\Models\Orcamento;
use Livewire\Component;
use Livewire\Attributes\On;

class OrcamentoHistory extends Component
{
    public int $orcamentoId;

    #[On('orcamento-atualizado')]
    public function refresh()
    {
        // Este método força o componente a se re-renderizar
    }

    // --- INÍCIO DO NOVO MÉTODO ---
    public function getFormattedDescription($atividade): string
    {
        // Pega os atributos que mudaram
        $attributes = $atividade->properties['attributes'] ?? [];
        $old = $atividade->properties['old'] ?? [];
        $camposDeMoeda = ['valor_total'];
        $descriptions = [];

        // Percorre cada campo que foi alterado
        foreach ($attributes as $key => $value) {
            $fieldName = str_replace('_', ' ', $key); // Troca 'valor_total' por 'valor total'
            $isMoeda = in_array($key, $camposDeMoeda);

            // Formata o novo valor
            $displayValue = $isMoeda ? 'R$ ' . number_format($value, 2, ',', '.') : '"' . e($value) . '"';

            $displayOldValue = '';
            // Se houver um valor antigo, formata-o também
            if (isset($old[$key])) {
                $oldValue = $old[$key];
                $displayOldValue = $isMoeda ? ' (Era: R$ ' . number_format($oldValue, 2, ',', '.') . ')' : ' (Era: "' . e($oldValue) . '")';
            }

            $descriptions[] = "O campo <strong class='dark:text-gray-300'>{$fieldName}</strong> foi alterado para <strong class='dark:text-gray-300'>{$displayValue}</strong>{$displayOldValue}.";
        }

        // Junta todas as descrições (se houver mais de uma) com uma quebra de linha
        return implode('<br>', $descriptions);
    }
    // --- FIM DO NOVO MÉTODO ---

    public function render()
    {
        $orcamento = Orcamento::find($this->orcamentoId);
        $atividades = $orcamento->activities()->latest()->get();

        return view('livewire.orcamento-history', ['atividades' => $atividades]);
    }
}