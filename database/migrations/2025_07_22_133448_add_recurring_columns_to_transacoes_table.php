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
        Schema::table('transacoes', function (Blueprint $table) {
            // Esta coluna marca se o lançamento é um "molde" recorrente.
            $table->boolean('is_recurring')->default(false)->after('pago');

            // Define a frequência. Por enquanto, vamos focar em 'monthly'.
            $table->string('frequency')->nullable()->after('is_recurring');

            // Guarda a data do próximo lançamento a ser gerado.
            $table->date('next_due_date')->nullable()->after('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacoes', function (Blueprint $table) {
            $table->dropColumn(['is_recurring', 'frequency', 'next_due_date']);
        });
    }
};
