<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TrabalhoArquivo extends Model
{
    use HasFactory;

    protected $table = 'trabalho_arquivos';

    protected $fillable = [
        'trabalho_id',
        'user_id',
        'nome_arquivo',
        'nome_original',
        'caminho',
        'tipo_mime',
        'tamanho',
        'categoria',
        'descricao',
        'visivel_cliente',
    ];

    protected $casts = [
        'visivel_cliente' => 'boolean',
        'tamanho' => 'integer',
    ];

    // Categorias disponíveis
    public const CATEGORIA_GERAL = 'geral';
    public const CATEGORIA_THUMB = 'thumb';
    public const CATEGORIA_FINAL = 'final';
    public const CATEGORIA_REVISAO = 'revisao';
    public const CATEGORIA_REFERENCIA = 'referencia';

    public static function getCategoriaOptions(): array
    {
        return [
            self::CATEGORIA_GERAL => 'Geral',
            self::CATEGORIA_THUMB => 'Thumbnail',
            self::CATEGORIA_FINAL => 'Arquivo Final',
            self::CATEGORIA_REVISAO => 'Revisão',
            self::CATEGORIA_REFERENCIA => 'Referência',
        ];
    }

    // Relacionamentos
    public function trabalho(): BelongsTo
    {
        return $this->belongsTo(Trabalho::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Métodos auxiliares
    public function getCategoriaLabelAttribute(): string
    {
        return self::getCategoriaOptions()[$this->categoria] ?? $this->categoria;
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->caminho);
    }

    public function getUrlDownloadAttribute(): string
    {
        return route('trabalho.arquivo.download', $this->id);
    }

    public function getIsImagemAttribute(): bool
    {
        return str_starts_with($this->tipo_mime, 'image/');
    }

    public function getIconeAttribute(): string
    {
        if ($this->is_imagem) {
            return 'photo';
        }
        
        return match(true) {
            str_contains($this->tipo_mime, 'pdf') => 'document-text',
            str_contains($this->tipo_mime, 'word') => 'document',
            str_contains($this->tipo_mime, 'excel') => 'table',
            str_contains($this->tipo_mime, 'powerpoint') => 'presentation-chart-bar',
            str_contains($this->tipo_mime, 'zip') || str_contains($this->tipo_mime, 'rar') => 'archive-box',
            str_contains($this->tipo_mime, 'video') => 'video-camera',
            str_contains($this->tipo_mime, 'audio') => 'musical-note',
            default => 'document',
        };
    }

    // Métodos de ação
    public function delete(): ?bool
    {
        // Deletar arquivo físico
        if (Storage::exists($this->caminho)) {
            Storage::delete($this->caminho);
        }
        
        return parent::delete();
    }

    public function moverPara(string $novaCategoria): bool
    {
        if (!array_key_exists($novaCategoria, self::getCategoriaOptions())) {
            return false;
        }
        
        $this->categoria = $novaCategoria;
        return $this->save();
    }
}