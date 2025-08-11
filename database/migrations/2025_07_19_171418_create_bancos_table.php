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
            Schema::create('bancos', function (Blueprint $table) {
                $table->id();
                $table->string('logo')->nullable(); // Caminho para a imagem do logo
                $table->string('nome');
                $table->string('tipo_conta'); // Ex: Corrente, Poupança, Investimentos
                $table->decimal('saldo_inicial', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
