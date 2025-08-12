<?php

namespace App\Livewire;

use App\Models\Trabalho;
use App\Models\Cliente;
use App\Models\User;
use App\Models\PortfolioCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class KanbanBoard extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    // Filtros
    public $filtroCliente = '';
    public $filtroCategoria = '';
    public $filtroResponsavel = '';
    public $filtroPrazoInicio = '';
    public $filtroPrazoFim = '';
    public $filtroBusca = '';
    public $agruparPorCliente = false;

    // Estado do componente
    public $trabalhoSelecionado = null;
    public $mostrarModal = false;
    public $carregando = false;

    // Dados para os filtros
    public $clientes = [];
    public $categorias = [];
    public $responsaveis = [];

    // Métricas
    public $metricas = [];

    protected $listeners = [
        'trabalhoMovido' => 'moverTrabalho',
        'atualizarPosicoes' => 'atualizarPosicoes',
        'recarregarKanban' => '$refresh',
        'fecharModal' => 'fecharModal',
    ];

    public function mount()
    {
        $this->carregarDadosFiltros();
        $this->calcularMetricas();
    }

    public function render()
    {
        $trabalhos = $this->obterTrabalhos();
        $trabalhosPorStatus = $this->agruparTrabalhosPorStatus($trabalhos);
        
        return view('livewire.kanban-board', [
            'trabalhosPorStatus' => $trabalhosPorStatus,
            'statusColunas' => $this->getStatusColunas(),
            'agrupadoPorCliente' => $this->agruparPorCliente,
        ]);
    }

    private function carregarDadosFiltros()
    {
        $this->clientes = Cliente::orderBy('name')->get();
        $this->categorias = PortfolioCategory::orderBy('name')->get();
        $this->responsaveis = User::orderBy('name')->get();
    }

    private function obterTrabalhos()
    {
        $query = Trabalho::with([
            'cliente',
            'responsavel',
            'categoria',
            'orcamento',
            'arquivos' => function($q) {
                $q->where('categoria', 'thumb')->latest()->limit(1);
            }
        ]);

        // Aplicar filtros
        if ($this->filtroCliente) {
            $query->where('cliente_id', $this->filtroCliente);
        }

        if ($this->filtroCategoria) {
            $query->where('categoria_id', $this->filtroCategoria);
        }

        if ($this->filtroResponsavel) {
            $query->where('responsavel_id', $this->filtroResponsavel);
        }

        if ($this->filtroPrazoInicio) {
            $query->where('prazo_entrega', '>=', $this->filtroPrazoInicio);
        }

        if ($this->filtroPrazoFim) {
            $query->where('prazo_entrega', '<=', $this->filtroPrazoFim);
        }

        if ($this->filtroBusca) {
            $query->where(function($q) {
                $q->where('titulo', 'like', '%' . $this->filtroBusca . '%')
                  ->orWhere('descricao', 'like', '%' . $this->filtroBusca . '%')
                  ->orWhereHas('cliente', function($clienteQuery) {
                      $clienteQuery->where('name', 'like', '%' . $this->filtroBusca . '%');
                  });
            });
        }

        // Verificar alertas antes de retornar
        $trabalhos = $query->ordenadoPorPosicao()->get();
        
        foreach ($trabalhos as $trabalho) {
            $trabalho->verificarAlertas();
        }

        return $trabalhos;
    }

    private function agruparTrabalhosPorStatus(Collection $trabalhos): array
    {
        $agrupados = [];
        
        foreach ($this->getStatusColunas() as $status => $config) {
            $trabalhosDaColuna = $trabalhos->where('status', $status);
            
            if ($this->agruparPorCliente) {
                $agrupados[$status] = $trabalhosDaColuna->groupBy('cliente.name');
            } else {
                $agrupados[$status] = $trabalhosDaColuna;
            }
        }
        
        return $agrupados;
    }

    private function getStatusColunas(): array
    {
        return [
            Trabalho::STATUS_BACKLOG => [
                'titulo' => 'Backlog',
                'cor' => 'bg-gray-100 border-gray-300',
                'icone' => 'inbox',
            ],
            Trabalho::STATUS_EM_PRODUCAO => [
                'titulo' => 'Em Produção',
                'cor' => 'bg-blue-100 border-blue-300',
                'icone' => 'cog',
            ],
            Trabalho::STATUS_EM_REVISAO => [
                'titulo' => 'Em Revisão',
                'cor' => 'bg-yellow-100 border-yellow-300',
                'icone' => 'eye',
            ],
            Trabalho::STATUS_FINALIZADO => [
                'titulo' => 'Finalizado',
                'cor' => 'bg-green-100 border-green-300',
                'icone' => 'check-circle',
            ],
            Trabalho::STATUS_PUBLICADO => [
                'titulo' => 'Publicado',
                'cor' => 'bg-purple-100 border-purple-300',
                'icone' => 'globe-alt',
            ],
        ];
    }

    public function moverTrabalho($trabalhoId, $novoStatus, $novaPosicao = 0)
    {
        $this->carregando = true;
        
        try {
            $trabalho = Trabalho::findOrFail($trabalhoId);
            $statusAnterior = $trabalho->status;
            
            // Verificar se a movimentação é permitida
            if (!$trabalho->podeSerMovidoPara($novoStatus)) {
                $this->dispatch('mostrarAlerta', [
                    'tipo' => 'erro',
                    'mensagem' => 'Movimentação não permitida para este status.'
                ]);
                $this->carregando = false;
                return;
            }
            
            // Remover verificação especial para publicação - mover diretamente
            
            // Mover trabalho
            $sucesso = $trabalho->moverPara($novoStatus, auth()->user());
            
            if ($sucesso) {
                $trabalho->atualizarPosicao($novaPosicao);
                
                // Executar integrações
                $this->executarIntegracoes($trabalho, $statusAnterior, $novoStatus);
                
                // Recalcular métricas
                $this->calcularMetricas();
                
                $this->dispatch('mostrarAlerta', [
                    'tipo' => 'sucesso',
                    'mensagem' => 'Trabalho movido com sucesso!'
                ]);
                
                // Disparar evento para atualizar a interface
                $this->dispatch('kanbanAtualizado');
            } else {
                $this->dispatch('mostrarAlerta', [
                    'tipo' => 'erro',
                    'mensagem' => 'Erro ao mover trabalho.'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro inesperado: ' . $e->getMessage()
            ]);
        } finally {
            $this->carregando = false;
        }
    }



    private function executarIntegracoes(Trabalho $trabalho, string $statusAnterior, string $novoStatus)
    {
        switch ($novoStatus) {
            case Trabalho::STATUS_EM_PRODUCAO:
                // Vincular ao orçamento aprovado se necessário
                if ($trabalho->orcamento && $trabalho->orcamento->status !== 'aprovado') {
                    $this->dispatch('sugerirAprovacaoOrcamento', $trabalho->orcamento_id);
                }
                break;
                
            case Trabalho::STATUS_FINALIZADO:
                // Sugerir lançamento de receita
                $this->dispatch('sugerirLancamentoReceita', [
                    'trabalho_id' => $trabalho->id,
                    'valor' => $trabalho->valor_total,
                    'cliente' => $trabalho->cliente->name
                ]);
                break;
                
            case Trabalho::STATUS_PUBLICADO:
                // Ativar no portfólio
                $this->ativarNoPortfolio($trabalho);
                break;
        }
    }

    private function ativarNoPortfolio(Trabalho $trabalho)
    {
        // Verificar se já existe um item no portfólio
        $portfolio = $trabalho->portfolio;
        
        if (!$portfolio) {
            // Criar novo item no portfólio
            $this->dispatch('criarItemPortfolio', [
                'orcamento_id' => $trabalho->orcamento_id,
                'titulo' => $trabalho->titulo,
                'descricao' => $trabalho->descricao,
                'categoria_id' => $trabalho->categoria_id
            ]);
        } else {
            // Ativar item existente
            $portfolio->update(['is_active' => true]);
        }
        
        $trabalho->update(['portfolio_ativo' => true]);
    }

    public function atualizarPosicoes($status, $trabalhoIds)
    {
        foreach ($trabalhoIds as $posicao => $trabalhoId) {
            Trabalho::where('id', $trabalhoId)->update([
                'posicao' => $posicao,
                'status' => $status
            ]);
        }
    }

    public function atualizarPosicao($trabalhoId, $novaPosicao)
    {
        try {
            $trabalho = Trabalho::findOrFail($trabalhoId);
            $trabalho->atualizarPosicao($novaPosicao);
            
            // Recalcular métricas
            $this->calcularMetricas();
            
            // Disparar evento para atualizar a interface
            $this->dispatch('kanbanAtualizado');
            
        } catch (\Exception $e) {
            $this->dispatch('mostrarAlerta', [
                'tipo' => 'erro',
                'mensagem' => 'Erro ao atualizar posição: ' . $e->getMessage()
            ]);
        }
    }

    public function abrirModal($trabalhoId)
    {
        $this->trabalhoSelecionado = $trabalhoId;
        $this->mostrarModal = true;
        $this->dispatch('abrirModalTrabalho', $trabalhoId);
    }

    public function fecharModal()
    {
        $this->trabalhoSelecionado = null;
        $this->mostrarModal = false;
    }

    public function limparFiltros()
    {
        $this->filtroCliente = '';
        $this->filtroCategoria = '';
        $this->filtroResponsavel = '';
        $this->filtroPrazoInicio = '';
        $this->filtroPrazoFim = '';
        $this->filtroBusca = '';
        $this->agruparPorCliente = false;
    }

    public function alternarAgrupamento()
    {
        $this->agruparPorCliente = !$this->agruparPorCliente;
    }

    private function calcularMetricas()
    {
        $trabalhos = Trabalho::all();
        
        // Lead time médio
        $leadTimes = $trabalhos->whereNotNull('lead_time')->pluck('lead_time');
        $leadTimeMedio = $leadTimes->count() > 0 ? $leadTimes->avg() : 0;
        
        // Contagem por fase
        $contagemPorFase = [];
        foreach (array_keys($this->getStatusColunas()) as $status) {
            $contagemPorFase[$status] = $trabalhos->where('status', $status)->count();
        }
        
        // Trabalhos atrasados
        $trabalhosAtrasados = $trabalhos->filter(function($trabalho) {
            return $trabalho->prazo_status === 'vencido';
        })->count();
        
        // Taxa de retrabalho
        $totalRetrabalho = $trabalhos->sum('contador_retrabalho');
        $totalTrabalhos = $trabalhos->count();
        $taxaRetrabalho = $totalTrabalhos > 0 ? ($totalRetrabalho / $totalTrabalhos) * 100 : 0;
        
        // Total de trabalhos
        $total = $trabalhos->count();
        
        // Trabalhos em andamento (em_producao + em_revisao)
        $emAndamento = $trabalhos->whereIn('status', [Trabalho::STATUS_EM_PRODUCAO, Trabalho::STATUS_EM_REVISAO])->count();
        
        $this->metricas = [
            'lead_time_medio' => round($leadTimeMedio, 1),
            'contagem_por_fase' => $contagemPorFase,
            'trabalhos_atrasados' => $trabalhosAtrasados,
            'taxa_retrabalho' => round($taxaRetrabalho, 1),
            'total' => $total,
            'em_andamento' => $emAndamento,
        ];
    }

    public function updated($property)
    {
        // Recalcular métricas quando filtros mudarem
        if (str_starts_with($property, 'filtro') || $property === 'agruparPorCliente') {
            $this->calcularMetricas();
        }
    }
    
    public function forcarAtualizacao()
    {
        // Força uma nova renderização do componente
        $this->calcularMetricas();
        $this->dispatch('kanbanAtualizado');
    }
}