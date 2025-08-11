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
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor', 15, 2);
            $table->date('data');
            $table->string('tipo'); // 'receita' ou 'despesa'

            // Conexão com a tabela de categorias
            $table->foreignId('categoria_id')->constrained('categorias');

            $table->boolean('pago')->default(true); // Para saber se já foi liquidado

            // Esta é a "conexão mágica" que pode apontar para um Banco ou um Cartão
            $table->morphs('transacionavel'); // Cria as colunas transacionavel_id e transacionavel_type

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacaos');
    }
};
