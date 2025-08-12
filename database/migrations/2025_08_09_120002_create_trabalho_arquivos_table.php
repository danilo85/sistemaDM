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
        Schema::create('trabalho_arquivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabalho_id')->constrained('trabalhos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Quem fez o upload
            
            $table->string('nome_arquivo');
            $table->string('nome_original'); // Nome original do arquivo
            $table->string('caminho', 500);
            $table->string('tipo_mime', 100);
            $table->bigInteger('tamanho'); // Tamanho em bytes
            $table->string('categoria')->default('geral'); // geral, thumb, final, revisao
            
            $table->text('descricao')->nullable();
            $table->boolean('visivel_cliente')->default(true);
            
            $table->timestamps();
            
            // Índices para performance
            $table->index('trabalho_id');
            $table->index('categoria');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabalho_arquivos');
    }
};