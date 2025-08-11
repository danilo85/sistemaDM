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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();

            // Chave estrangeira para o pagamento que originou este recibo
            // O constrained() garante a integridade, e o cascadeOnDelete() apaga o recibo se o pagamento for excluído.
            $table->foreignId('pagamento_id')->constrained()->cascadeOnDelete();

            $table->string('numero')->unique(); // Número único para cada recibo
            $table->date('data_emissao'); // Data em que o recibo foi gerado
            $table->decimal('valor', 10, 2); // Valor do recibo (copiado do pagamento)
            $table->string('token')->unique(); // Token de acesso público e seguro

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
