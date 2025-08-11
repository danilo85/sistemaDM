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
            Schema::table('bancos', function (Blueprint $table) {
                // Aumenta o tamanho total para 15 dígitos, mantendo 2 para centavos
                $table->decimal('saldo_inicial', 15, 2)->default(0.00)->change();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bancos', function (Blueprint $table) {
            //
        });
    }
};
