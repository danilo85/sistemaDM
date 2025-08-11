<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        // database/migrations/2025_07_18_143436_create_orcamentos_table.php

        public function up(): void
        {
            Schema::create('orcamentos', function (Blueprint $table) {
                $table->id();
                $table->integer('numero')->unique();
                $table->string('titulo');
                $table->text('descricao');

                $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // O "autor"

                $table->string('status')->default('Analisando'); // Padrão Analisando (amarelo)
                $table->decimal('valor_total', 15, 2);
                $table->text('condicoes_pagamento')->nullable();

                $table->date('data_emissao');
                $table->date('data_validade')->nullable();
                $table->integer('prazo_entrega_dias')->nullable();
                $table->date('data_inicio_projeto')->nullable();

                $table->boolean('visivel')->default(true);
                $table->boolean('fixado')->default(false);

                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos');
    }
};
