<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trabalhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('categoria_id')->nullable()->constrained('portfolio_categories')->onDelete('set null');
            
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('status', ['backlog', 'em_producao', 'em_revisao', 'finalizado', 'publicado'])->default('backlog');
            $table->decimal('valor_total', 15, 2)->default(0.00);
            
            $table->date('prazo_entrega')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_finalizacao')->nullable();
            $table->boolean('portfolio_ativo')->default(false);
            
            // Campos para controle do Kanban
            $table->integer('posicao')->default(0); // Para ordenação dentro da coluna
            $table->json('configuracoes')->nullable(); // Configurações específicas do trabalho
            
            // Campos para métricas e alertas
            $table->timestamp('data_ultima_movimentacao')->nullable();
            $table->integer('contador_retrabalho')->default(0);
            $table->boolean('alerta_prazo')->default(false);
            $table->boolean('alerta_orcamento')->default(false);
            
            $table->timestamps();
            
            // Índices para performance
            $table->index('status');
            $table->index('cliente_id');
            $table->index('responsavel_id');
            $table->index('prazo_entrega');
            $table->index(['status', 'posicao']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabalhos');
    }
};