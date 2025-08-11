<?php

namespace App\Console\Commands;

use App\Models\Transacao;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GerarLancamentosRecorrentes extends Command
{
    /**
     * O nome e a assinatura do nosso comando no terminal.
     */
    protected $signature = 'lancamentos:gerar-recorrentes';

    /**
     * A descrição do comando.
     */
    protected $description = 'Verifica e gera lançamentos recorrentes que estão vencidos.';

    /**
     * Executa a lógica do comando.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de lançamentos recorrentes...');

        // 1. Busca todos os "moldes" recorrentes cuja próxima data de vencimento já passou ou é hoje.
        $moldesParaGerar = Transacao::where('is_recurring', true)
                                    ->where('next_due_date', '<=', now())
                                    ->get();

        if ($moldesParaGerar->isEmpty()) {
            $this->info('Nenhum lançamento recorrente para gerar hoje.');
            return;
        }

        foreach ($moldesParaGerar as $molde) {
            // 2. Cria uma nova transação real, copiando os dados do molde.
            Transacao::create([
                'descricao' => $molde->descricao,
                'valor' => $molde->valor,
                'data' => $molde->next_due_date, // A data do lançamento é a data de vencimento
                'tipo' => $molde->tipo,
                'categoria_id' => $molde->categoria_id,
                'pago' => false, // O novo lançamento sempre começa como "não pago"
                'transacionavel_id' => $molde->transacionavel_id,
                'transacionavel_type' => $molde->transacionavel_type,
            ]);

            // 3. Atualiza o "molde" com a próxima data de vencimento.
            if ($molde->frequency === 'monthly') {
                $molde->next_due_date = Carbon::parse($molde->next_due_date)->addMonth();
            } elseif ($molde->frequency === 'yearly') {
                $molde->next_due_date = Carbon::parse($molde->next_due_date)->addYear();
            }
            $molde->save();

            $this->info("Lançamento gerado para: '{$molde->descricao}' com data de {$molde->next_due_date->format('d/m/Y')}");
        }

        $this->info('Verificação concluída com sucesso!');
    }
}