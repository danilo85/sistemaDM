<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Trabalho extends Model
{
    use HasFactory;

    protected $table = 'trabalhos';

    protected $fillable = [
        'orcamento_id',
        'cliente_id',
        'responsavel_id',
        'categoria_id',
        'titulo',
        'descricao',
        'status',
        'valor_total',
        'prazo_entrega',
        'data_inicio',
        'data_finalizacao',
        'portfolio_ativo',
        'posicao',
        'configuracoes',
        'data_ultima_movimentacao',
        'contador_retrabalho',
        'alerta_prazo',
        'alerta_orcamento',
    ];

    protected $casts = [
        'prazo_entrega' => 'date',
        'data_inicio' => 'date',
        'data_finalizacao' => 'date',
        'data_ultima_movimentacao' => 'datetime',
        'portfolio_ativo' => 'boolean',
        'alerta_prazo' => 'boolean',
        'alerta_orcamento' => 'boolean',
        'configuracoes' => 'array',
        'valor_total' => 'decimal:2',
        'contador_retrabalho' => 'integer',
        'posicao' => 'integer',
    ];

    // Status disponíveis
    public const STATUS_BACKLOG = 'backlog';
    public const STATUS_EM_PRODUCAO = 'em_producao';
    public const STATUS_EM_REVISAO = 'em_revisao';
    public const STATUS_FINALIZADO = 'finalizado';
    public const STATUS_PUBLICADO = 'publicado';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_BACKLOG => 'Backlog',
            self::STATUS_EM_PRODUCAO => 'Em Produção',
            self::STATUS_EM_REVISAO => 'Em Revisão',
            self::STATUS_FINALIZADO => 'Finalizado',
            self::STATUS_PUBLICADO => 'Publicado',
        ];
    }

    // Relacionamentos
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'categoria_id');
    }

    public function historico(): HasMany
    {
        return $this->hasMany(TrabalhoHistorico::class)->orderBy('created_at', 'desc');
    }

    public function arquivos(): HasMany
    {
        return $this->hasMany(TrabalhoArquivo::class)->orderBy('created_at', 'desc');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(TrabalhoComentario::class)->orderBy('created_at', 'desc');
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class, 'orcamento_id', 'orcamento_id');
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCliente($query, int $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    public function scopeByResponsavel($query, int $responsavelId)
    {
        return $query->where('responsavel_id', $responsavelId);
    }

    public function scopeComPrazoProximo($query, int $dias = 3)
    {
        return $query->where('prazo_entrega', '<=', now()->addDays($dias))
                    ->where('prazo_entrega', '>=', now());
    }

    public function scopeAtrasados($query)
    {
        return $query->where('prazo_entrega', '<', now())
                    ->whereNotIn('status', [self::STATUS_FINALIZADO, self::STATUS_PUBLICADO]);
    }

    public function scopeOrdenadoPorPosicao($query)
    {
        return $query->orderBy('posicao')->orderBy('created_at');
    }

    // Métodos auxiliares
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_BACKLOG => 'bg-gray-100 text-gray-800',
            self::STATUS_EM_PRODUCAO => 'bg-blue-100 text-blue-800',
            self::STATUS_EM_REVISAO => 'bg-yellow-100 text-yellow-800',
            self::STATUS_FINALIZADO => 'bg-green-100 text-green-800',
            self::STATUS_PUBLICADO => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPrazoStatusAttribute(): string
    {
        if (!$this->prazo_entrega) {
            return 'sem-prazo';
        }

        $hoje = now()->startOfDay();
        $prazo = $this->prazo_entrega->startOfDay();
        $diasRestantes = $hoje->diffInDays($prazo, false);

        if ($diasRestantes < 0) {
            return 'vencido';
        } elseif ($diasRestantes <= 3) {
            return 'proximo';
        } else {
            return 'normal';
        }
    }

    public function getPrazoColorAttribute(): string
    {
        return match($this->prazo_status) {
            'vencido' => 'border-red-500 bg-red-50',
            'proximo' => 'border-yellow-500 bg-yellow-50',
            'normal' => 'border-green-500 bg-green-50',
            default => 'border-gray-300 bg-white',
        };
    }

    public function getDiasRestantesAttribute(): ?int
    {
        if (!$this->prazo_entrega) {
            return null;
        }

        return now()->startOfDay()->diffInDays($this->prazo_entrega->startOfDay(), false);
    }

    public function getTemArquivosAttribute(): bool
    {
        return $this->arquivos()->count() > 0;
    }

    public function getTemComentariosAttribute(): bool
    {
        return $this->comentarios()->count() > 0;
    }

    public function getProntoParaPortfolioAttribute(): bool
    {
        $temThumb = $this->arquivos()->where('categoria', 'thumb')->exists();
        $temDescricao = !empty($this->descricao);
        
        return $temThumb && $temDescricao;
    }

    public function getLeadTimeAttribute(): ?int
    {
        if (!$this->data_finalizacao || !$this->data_inicio) {
            return null;
        }

        return $this->data_inicio->diffInDays($this->data_finalizacao);
    }

    // Métodos de ação
    public function moverPara(string $novoStatus, User $usuario, ?string $observacoes = null): bool
    {
        $statusAnterior = $this->status;
        
        // Validar se a movimentação é permitida
        if (!$this->podeSerMovidoPara($novoStatus)) {
            return false;
        }

        // Atualizar status e dados relacionados
        $this->status = $novoStatus;
        $this->data_ultima_movimentacao = now();
        
        // Lógica específica por status
        switch ($novoStatus) {
            case self::STATUS_EM_PRODUCAO:
                if (!$this->data_inicio) {
                    $this->data_inicio = now();
                }
                break;
                
            case self::STATUS_FINALIZADO:
                $this->data_finalizacao = now();
                break;
                
            case self::STATUS_EM_REVISAO:
                if ($statusAnterior === self::STATUS_EM_REVISAO) {
                    $this->contador_retrabalho++;
                }
                break;
        }

        $this->save();

        // Registrar no histórico
        $this->historico()->create([
            'user_id' => $usuario->id,
            'status_anterior' => $statusAnterior,
            'status_novo' => $novoStatus,
            'observacoes' => $observacoes,
        ]);

        return true;
    }

    public function podeSerMovidoPara(string $status): bool
    {
        $statusAtual = $this->status;
        
        // Regras de movimentação
        $regras = [
            self::STATUS_BACKLOG => [self::STATUS_EM_PRODUCAO],
            self::STATUS_EM_PRODUCAO => [self::STATUS_EM_REVISAO, self::STATUS_BACKLOG],
            self::STATUS_EM_REVISAO => [self::STATUS_FINALIZADO, self::STATUS_EM_PRODUCAO],
            self::STATUS_FINALIZADO => [self::STATUS_PUBLICADO, self::STATUS_EM_REVISAO],
            self::STATUS_PUBLICADO => [self::STATUS_FINALIZADO], // Apenas para correções
        ];

        return in_array($status, $regras[$statusAtual] ?? []);
    }

    public function atualizarPosicao(int $novaPosicao): void
    {
        $this->update(['posicao' => $novaPosicao]);
    }

    public function verificarAlertas(): void
    {
        // Alerta de prazo
        $this->alerta_prazo = $this->prazo_status === 'vencido' || $this->prazo_status === 'proximo';
        
        // Alerta de orçamento (se visualizado há mais de 7 dias sem aprovação)
        if ($this->orcamento && $this->orcamento->status !== 'aprovado') {
            $diasVisualizacao = $this->orcamento->updated_at->diffInDays(now());
            $this->alerta_orcamento = $diasVisualizacao > 7;
        }
        
        $this->save();
    }
}