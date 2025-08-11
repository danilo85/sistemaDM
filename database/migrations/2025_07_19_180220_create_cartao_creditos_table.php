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
        Schema::create('cartoes_credito', function (Blueprint $table) {
            $table->id();
            $table->string('bandeira')->nullable(); // Caminho para a imagem da bandeira
            $table->string('nome'); // Ex: "Nubank Ultravioleta", "Inter Gold"
            $table->decimal('limite_credito', 10, 2);
            $table->integer('dia_fechamento'); // Dia do mês que a fatura fecha
            $table->integer('dia_vencimento'); // Dia do mês que a fatura vence
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartao_creditos');
    }
};
