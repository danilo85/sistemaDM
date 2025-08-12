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
            $table->string('status')->default('pago')->after('pago');
            
            // Adiciona índice para melhor performance
            $table->index('status');
        });
        
        // Atualiza registros existentes baseado na coluna 'pago'
        DB::statement("UPDATE transacoes SET status = CASE WHEN pago = 1 THEN 'pago' ELSE 'pendente' END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacoes', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};