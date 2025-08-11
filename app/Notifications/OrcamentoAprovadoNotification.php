<?php

namespace App\Notifications;

use App\Models\Orcamento;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrcamentoAprovadoNotification extends Notification
{
    use Queueable;

    public $orcamento;

    public function __construct(Orcamento $orcamento)
    {
        $this->orcamento = $orcamento;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Vamos guardar esta notificação no banco de dados
    }

    // Define os dados que serão guardados na coluna 'data' da tabela
    public function toArray(object $notifiable): array
    {
        return [
            'orcamento_id' => $this->orcamento->id,
            'orcamento_titulo' => $this->orcamento->titulo,
            'cliente_nome' => $this->orcamento->cliente->name,
            'mensagem' => 'O orçamento foi aprovado pelo cliente!',
            'url' => route('orcamentos.show', $this->orcamento->id),
        ];
    }
}
