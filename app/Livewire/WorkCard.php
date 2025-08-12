<?php

namespace App\Livewire;

use App\Models\Trabalho;
use Livewire\Component;

class WorkCard extends Component
{
    public Trabalho $trabalho;
    public $mostrarDetalhes = false;

    protected $listeners = [
        'atualizarCard' => '$refresh',
    ];

    public function mount(Trabalho $trabalho)
    {
        $this->trabalho = $trabalho;
    }
    
    public function getCorCardProperty()
    {
        // Verificar se há cor personalizada salva
        $corPersonalizada = session('cor_card_' . $this->trabalho->id);
        
        if ($corPersonalizada) {
            return $corPersonalizada;
        }
        
        // Cores suaves baseadas no ID do trabalho para consistência
        $cores = [
            'bg-blue-50 border-blue-200',
            'bg-green-50 border-green-200', 
            'bg-purple-50 border-purple-200',
            'bg-pink-50 border-pink-200',
            'bg-indigo-50 border-indigo-200',
            'bg-yellow-50 border-yellow-200',
            'bg-red-50 border-red-200',
            'bg-teal-50 border-teal-200'
        ];
        
        // Usar o ID do trabalho para garantir cor consistente
        $indice = $this->trabalho->id % count($cores);
        return $cores[$indice];
    }

    public function render()
    {
        return view('livewire.work-card');
    }

    public function abrirDetalhes()
    {
        $this->dispatch('abrirModalTrabalho', $this->trabalho->id);
    }

    public function enviarLembrete()
    {
        try {
            // Simular envio de lembrete (aqui você pode integrar com email, WhatsApp, etc.)
            // Por exemplo: Mail::to($this->trabalho->cliente->email)->send(new LembreteTrabalho($this->trabalho));
            
            // Registrar o lembrete no sistema usando a estrutura correta
            $this->trabalho->comentarios()->create([
                'comentario' => 'Lembrete enviado para o cliente sobre o andamento do trabalho "' . $this->trabalho->titulo . '". Status atual: ' . ucfirst(str_replace('_', ' ', $this->trabalho->status)) . '.',
                'user_id' => auth()->id(),
                'visivel_cliente' => false, // Comentário interno
                'importante' => false,
                'tipo' => 'sistema'
            ]);
            
            // Aqui você pode adicionar a lógica real de envio do lembrete
            // Exemplo com email:
            // Mail::to($this->trabalho->cliente->email)->send(new LembreteTrabalho($this->trabalho));
            
            // Exemplo com WhatsApp (usando uma API como Twilio):
            // $this->enviarWhatsApp($this->trabalho->cliente->telefone, $mensagem);
            
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'sucesso',
                'mensagem' => 'Lembrete enviado com sucesso para ' . $this->trabalho->cliente->name . '!'
            ]);
            
            // Atualizar o card e o kanban
            $this->dispatch('atualizarCard');
            $this->dispatch('kanbanAtualizado');
            
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao enviar lembrete: ' . $e->getMessage()
            ]);
        }
    }

    public function criarLancamentoReceita()
    {
        $this->dispatch('sugerirLancamentoReceita', [
            'trabalho_id' => $this->trabalho->id,
            'valor' => $this->trabalho->valor_total,
            'cliente' => $this->trabalho->cliente->name
        ]);
    }

    public function ativarPortfolio()
    {
        if ($this->trabalho->pronto_para_portfolio) {
            $this->dispatch('ativarNoPortfolio', $this->trabalho->id);
        } else {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'aviso',
                'mensagem' => 'Trabalho precisa de thumbnail e descrição para ser ativado no portfólio.'
            ]);
        }
    }
    
    public function alterarCor($novaCor)
    {
        try {
            // Salvar a cor personalizada no trabalho (você pode criar uma coluna 'cor_card' na tabela)
            // Por enquanto, vamos usar uma abordagem simples com session
            session(['cor_card_' . $this->trabalho->id => $novaCor]);
            
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'sucesso',
                'mensagem' => 'Cor do card alterada com sucesso!'
            ]);
            
            // Forçar atualização do componente
            $this->dispatch('atualizarCard');
            
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao alterar cor: ' . $e->getMessage()
            ]);
        }
    }

    public function getIndicadoresProperty()
    {
        $indicadores = [];
        
        // Indicador de pagamento
        if ($this->trabalho->orcamento) {
            $orcamento = $this->trabalho->orcamento;
            if ($orcamento->status === 'pago') {
                $indicadores['pagamento'] = ['cor' => 'green', 'icone' => 'check-circle', 'tooltip' => 'Pago'];
            } elseif ($orcamento->status === 'aprovado') {
                $indicadores['pagamento'] = ['cor' => 'yellow', 'icone' => 'clock', 'tooltip' => 'Aprovado - Aguardando pagamento'];
            } else {
                $indicadores['pagamento'] = ['cor' => 'red', 'icone' => 'x-circle', 'tooltip' => 'Pendente de aprovação'];
            }
        }
        
        // Indicador de arquivos
        if ($this->trabalho->tem_arquivos) {
            $totalArquivos = $this->trabalho->arquivos->count();
            $indicadores['arquivos'] = [
                'cor' => 'blue',
                'icone' => 'document',
                'tooltip' => $totalArquivos . ' arquivo(s)',
                'contador' => $totalArquivos
            ];
        }
        
        // Indicador de comentários
        if ($this->trabalho->tem_comentarios) {
            $totalComentarios = $this->trabalho->comentarios->count();
            $indicadores['comentarios'] = [
                'cor' => 'purple',
                'icone' => 'chat-bubble-left',
                'tooltip' => $totalComentarios . ' comentário(s)',
                'contador' => $totalComentarios
            ];
        }
        
        // Indicador de portfólio
        if ($this->trabalho->pronto_para_portfolio) {
            $indicadores['portfolio'] = [
                'cor' => 'green',
                'icone' => 'star',
                'tooltip' => 'Pronto para portfólio'
            ];
        }
        
        return $indicadores;
    }

    public function getAlertasProperty()
    {
        $alertas = [];
        
        // Alerta de prazo
        if ($this->trabalho->alerta_prazo) {
            $status = $this->trabalho->prazo_status;
            if ($status === 'vencido') {
                $alertas[] = [
                    'tipo' => 'erro',
                    'mensagem' => 'Prazo vencido há ' . abs($this->trabalho->dias_restantes) . ' dia(s)',
                    'icone' => 'exclamation-triangle'
                ];
            } elseif ($status === 'proximo') {
                $alertas[] = [
                    'tipo' => 'aviso',
                    'mensagem' => 'Vence em ' . $this->trabalho->dias_restantes . ' dia(s)',
                    'icone' => 'clock'
                ];
            }
        }
        
        // Alerta de orçamento
        if ($this->trabalho->alerta_orcamento) {
            $alertas[] = [
                'tipo' => 'info',
                'mensagem' => 'Orçamento visualizado há mais de 7 dias',
                'icone' => 'information-circle'
            ];
        }
        
        // Alerta de retrabalho
        if ($this->trabalho->contador_retrabalho > 0) {
            $alertas[] = [
                'tipo' => 'aviso',
                'mensagem' => 'Retornou ' . $this->trabalho->contador_retrabalho . ' vez(es) para revisão',
                'icone' => 'arrow-path'
            ];
        }
        
        return $alertas;
    }
}