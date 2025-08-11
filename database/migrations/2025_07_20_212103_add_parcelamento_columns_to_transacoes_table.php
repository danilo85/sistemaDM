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
            // Um identificador único para agrupar todas as parcelas de uma mesma compra. Pode ser nulo para compras à vista.
            $table->uuid('compra_id')->nullable()->after('pago');

            // O número da parcela atual (ex: 1, 2, 3...). Pode ser nulo para compras à vista.
            $table->integer('parcela_atual')->nullable()->after('compra_id');

            // O número total de parcelas da compra (ex: 12). Pode ser nulo para compras à vista.
            $table->integer('parcela_total')->nullable()->after('parcela_atual');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacoes', function (Blueprint $table) {
            $table->dropColumn(['compra_id', 'parcela_atual', 'parcela_total']);
        });
    }
};
