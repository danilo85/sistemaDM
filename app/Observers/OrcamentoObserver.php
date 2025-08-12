<?php

namespace App\Observers;

use App\Models\Orcamento;
use App\Models\Trabalho;
use Carbon\Carbon;

class OrcamentoObserver
{
    /**
     * Handle the Orcamento "created" event.
     */
    public function created(Orcamento $orcamento): void
    {
        //
    }

    /**
     * Handle the Orcamento "updated" event.
     */
    public function updated(Orcamento $orcamento): void
    {
        // Verificar se o status mudou para 'Aprovado' e ainda não existe um trabalho
        if ($orcamento->isDirty('status') && $orcamento->status === 'Aprovado') {
            // Verificar se já existe um trabalho para este orçamento
            $trabalhoExistente = Trabalho::where('orcamento_id', $orcamento->id)->first();
            
            if (!$trabalhoExistente) {
                // Calcular prazo de entrega baseado no prazo do orçamento
                $prazoEntrega = null;
                if ($orcamento->prazo_entrega_dias) {
                    $prazoEntrega = now()->addDays($orcamento->prazo_entrega_dias);
                }
                
                // Criar o trabalho
                Trabalho::create([
                    'orcamento_id' => $orcamento->id,
                    'cliente_id' => $orcamento->cliente_id,
                    'titulo' => $orcamento->titulo,
                    'descricao' => $orcamento->descricao,
                    'valor_total' => $orcamento->valor_total,
                    'prazo_entrega' => $prazoEntrega,
                    'status' => 'backlog',
                    'posicao' => 0,
                    'data_ultima_movimentacao' => now(),
                ]);
            }
        }
    }

    /**
     * Handle the Orcamento "deleted" event.
     */
    public function deleted(Orcamento $orcamento): void
    {
        //
    }

    /**
     * Handle the Orcamento "restored" event.
     */
    public function restored(Orcamento $orcamento): void
    {
        //
    }

    /**
     * Handle the Orcamento "force deleted" event.
     */
    public function forceDeleted(Orcamento $orcamento): void
    {
        //
    }
}
