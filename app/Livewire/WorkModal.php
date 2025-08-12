<?php

namespace App\Livewire;

use App\Models\Trabalho;
use App\Models\TrabalhoComentario;
use App\Models\TrabalhoArquivo;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class WorkModal extends Component
{
    use WithFileUploads;

    public $trabalhoId;
    public $trabalho;
    public $abaSelecionada = 'detalhes';
    
    // Formulários
    public $novoComentario = '';
    public $comentarioVisivelCliente = false;
    public $comentarioImportante = false;
    public $arquivos = [];
    public $categoriaArquivo = 'geral';
    public $descricaoArquivo = '';
    
    // Estado
    public $carregandoUpload = false;
    public $mostrarFormComentario = false;
    public $mostrarFormArquivo = false;

    protected $listeners = [
        'abrirModalTrabalho' => 'abrirModal',
        'fecharModal' => 'fecharModal',
    ];

    protected $rules = [
        'novoComentario' => 'required|min:3|max:1000',
        'arquivos.*' => 'file|max:10240', // 10MB
        'categoriaArquivo' => 'required|in:geral,thumb,final,revisao,referencia',
    ];

    public function abrirModal($trabalhoId)
    {
        $this->trabalhoId = $trabalhoId;
        $this->carregarTrabalho();
        $this->abaSelecionada = 'detalhes';
        $this->resetarFormularios();
    }

    public function fecharModal()
    {
        $this->trabalhoId = null;
        $this->trabalho = null;
        $this->resetarFormularios();
        $this->dispatch('fecharModal');
    }

    public function render()
    {
        if (!$this->trabalho) {
            return view('livewire.work-modal-empty');
        }

        return view('livewire.work-modal', [
            'abas' => $this->getAbas(),
            'categoriaArquivos' => TrabalhoArquivo::getCategoriaOptions(),
        ]);
    }

    private function carregarTrabalho()
    {
        $this->trabalho = Trabalho::with([
            'cliente',
            'responsavel',
            'categoria',
            'orcamento',
            'historico.usuario',
            'arquivos.usuario',
            'comentarios.usuario',
            'portfolio'
        ])->find($this->trabalhoId);
    }

    private function getAbas()
    {
        return [
            'detalhes' => [
                'titulo' => 'Detalhes',
                'icone' => 'information-circle',
                'contador' => null
            ],
            'historico' => [
                'titulo' => 'Histórico',
                'icone' => 'clock',
                'contador' => $this->trabalho->historico->count()
            ],
            'arquivos' => [
                'titulo' => 'Arquivos',
                'icone' => 'document',
                'contador' => $this->trabalho->arquivos->count()
            ],
            'comentarios' => [
                'titulo' => 'Comentários',
                'icone' => 'chat-bubble-left',
                'contador' => $this->trabalho->comentarios->count()
            ],
        ];
    }

    public function selecionarAba($aba)
    {
        $this->abaSelecionada = $aba;
        $this->resetarFormularios();
    }

    public function adicionarComentario()
    {
        $this->validate([
            'novoComentario' => 'required|min:3|max:1000'
        ]);

        TrabalhoComentario::create([
            'trabalho_id' => $this->trabalho->id,
            'user_id' => auth()->id(),
            'comentario' => $this->novoComentario,
            'visivel_cliente' => $this->comentarioVisivelCliente,
            'importante' => $this->comentarioImportante,
        ]);

        $this->novoComentario = '';
        $this->comentarioVisivelCliente = false;
        $this->comentarioImportante = false;
        $this->mostrarFormComentario = false;
        
        $this->carregarTrabalho();
        
        $this->dispatch('mostrarAlerta', [
            'tipo' => 'sucesso',
            'mensagem' => 'Comentário adicionado com sucesso!'
        ]);
    }

    public function uploadArquivos()
    {
        $this->validate([
            'arquivos.*' => 'file|max:10240',
            'categoriaArquivo' => 'required',
        ]);

        $this->carregandoUpload = true;

        try {
            foreach ($this->arquivos as $arquivo) {
                $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
                $caminho = $arquivo->storeAs('trabalhos/' . $this->trabalho->id, $nomeArquivo, 'public');

                TrabalhoArquivo::create([
                    'trabalho_id' => $this->trabalho->id,
                    'user_id' => auth()->id(),
                    'nome_arquivo' => $nomeArquivo,
                    'nome_original' => $arquivo->getClientOriginalName(),
                    'caminho' => $caminho,
                    'tipo_mime' => $arquivo->getMimeType(),
                    'tamanho' => $arquivo->getSize(),
                    'categoria' => $this->categoriaArquivo,
                    'descricao' => $this->descricaoArquivo,
                ]);
            }

            $this->arquivos = [];
            $this->categoriaArquivo = 'geral';
            $this->descricaoArquivo = '';
            $this->mostrarFormArquivo = false;
            
            $this->carregarTrabalho();
            
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'sucesso',
                'mensagem' => 'Arquivo(s) enviado(s) com sucesso!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao enviar arquivo: ' . $e->getMessage()
            ]);
        } finally {
            $this->carregandoUpload = false;
        }
    }

    public function excluirArquivo($arquivoId)
    {
        try {
            $arquivo = TrabalhoArquivo::findOrFail($arquivoId);
            
            // Verificar permissão
            if ($arquivo->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                $this->dispatch('mostrarAlerta', [
                    'tipo' => 'erro',
                    'mensagem' => 'Você não tem permissão para excluir este arquivo.'
                ]);
                return;
            }
            
            $arquivo->delete();
            $this->carregarTrabalho();
            
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'sucesso',
                'mensagem' => 'Arquivo excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao excluir arquivo.'
            ]);
        }
    }

    public function alternarVisibilidadeComentario($comentarioId)
    {
        try {
            $comentario = TrabalhoComentario::findOrFail($comentarioId);
            $comentario->alternarVisibilidadeCliente();
            
            $this->carregarTrabalho();
            
            $status = $comentario->visivel_cliente ? 'visível' : 'oculto';
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'sucesso',
                'mensagem' => "Comentário agora está {$status} para o cliente."
            ]);
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao alterar visibilidade do comentário.'
            ]);
        }
    }

    public function marcarComentarioImportante($comentarioId)
    {
        try {
            $comentario = TrabalhoComentario::findOrFail($comentarioId);
            
            if ($comentario->importante) {
                $comentario->desmarcarImportante();
                $mensagem = 'Comentário desmarcado como importante.';
            } else {
                $comentario->marcarComoImportante();
                $mensagem = 'Comentário marcado como importante.';
            }
            
            $this->carregarTrabalho();
            
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'sucesso',
                'mensagem' => $mensagem
            ]);
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao alterar status do comentário.'
            ]);
        }
    }

    public function enviarLembrete()
    {
        // Implementar lógica de envio de lembrete
        $this->dispatch('mostrarAlerta', [
            'tipo' => 'info',
            'mensagem' => 'Lembrete enviado para ' . $this->trabalho->cliente->name
        ]);
    }

    public function criarLancamentoReceita()
    {
        $this->dispatch('sugerirLancamentoReceita', [
            'trabalho_id' => $this->trabalho->id,
            'valor' => $this->trabalho->valor_total,
            'cliente' => $this->trabalho->cliente->name
        ]);
    }

    public function ativarNoPortfolio()
    {
        if (!$this->trabalho->pronto_para_portfolio) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'aviso',
                'mensagem' => 'Trabalho precisa de thumbnail e descrição para ser ativado no portfólio.'
            ]);
            return;
        }

        try {
            // Lógica para ativar no portfólio
            $this->trabalho->update(['portfolio_ativo' => true]);
            
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'sucesso',
                'mensagem' => 'Trabalho ativado no portfólio com sucesso!'
            ]);
            
            $this->carregarTrabalho();
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao ativar no portfólio.'
            ]);
        }
    }

    public function alterarStatus($novoStatus)
    {
        try {
            $sucesso = $this->trabalho->moverPara($novoStatus, auth()->user());
            
            if ($sucesso) {
                $this->carregarTrabalho();
                $this->dispatch('recarregarKanban');
                
                $this->dispatch('mostrarAlerta', [
                    'tipo' => 'sucesso',
                    'mensagem' => 'Status alterado com sucesso!'
                ]);
            } else {
                $this->dispatch('mostrarAlerta', [
                    'tipo' => 'erro',
                    'mensagem' => 'Não é possível mover para este status.'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao alterar status.'
            ]);
        }
    }

    private function resetarFormularios()
    {
        $this->novoComentario = '';
        $this->comentarioVisivelCliente = false;
        $this->comentarioImportante = false;
        $this->arquivos = [];
        $this->categoriaArquivo = 'geral';
        $this->descricaoArquivo = '';
        $this->mostrarFormComentario = false;
        $this->mostrarFormArquivo = false;
        $this->carregandoUpload = false;
    }

    public function toggleFormComentario()
    {
        $this->mostrarFormComentario = !$this->mostrarFormComentario;
        $this->mostrarFormArquivo = false;
    }

    public function toggleFormArquivo()
    {
        $this->mostrarFormArquivo = !$this->mostrarFormArquivo;
        $this->mostrarFormComentario = false;
    }
}