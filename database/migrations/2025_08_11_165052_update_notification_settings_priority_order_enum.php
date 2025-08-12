<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            // Primeiro, alterar a coluna para VARCHAR temporariamente
            $table->string('priority_order_temp')->default('high_first');
        });
        
        // Copiar dados existentes convertendo os valores
        DB::statement("
            UPDATE notification_settings 
            SET priority_order_temp = CASE 
                WHEN priority_order = 'deadlines_first' THEN 'high_first'
                WHEN priority_order = 'approvals_first' THEN 'low_first'
                ELSE 'chronological'
            END
        ");
        
        Schema::table('notification_settings', function (Blueprint $table) {
            // Remover a coluna antiga
            $table->dropColumn('priority_order');
        });
        
        Schema::table('notification_settings', function (Blueprint $table) {
            // Criar a nova coluna com os valores corretos
            $table->enum('priority_order', ['high_first', 'low_first', 'chronological'])->default('high_first');
        });
        
        // Copiar os dados da coluna temporária para a nova
        DB::statement("UPDATE notification_settings SET priority_order = priority_order_temp");
        
        Schema::table('notification_settings', function (Blueprint $table) {
            // Remover a coluna temporária
            $table->dropColumn('priority_order_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            // Primeiro, alterar a coluna para VARCHAR temporariamente
            $table->string('priority_order_temp')->default('deadlines_first');
        });
        
        // Copiar dados existentes convertendo os valores de volta
        DB::statement("
            UPDATE notification_settings 
            SET priority_order_temp = CASE 
                WHEN priority_order = 'high_first' THEN 'deadlines_first'
                WHEN priority_order = 'low_first' THEN 'approvals_first'
                ELSE 'deadlines_first'
            END
        ");
        
        Schema::table('notification_settings', function (Blueprint $table) {
            // Remover a coluna atual
            $table->dropColumn('priority_order');
        });
        
        Schema::table('notification_settings', function (Blueprint $table) {
            // Recriar a coluna com os valores antigos
            $table->enum('priority_order', ['deadlines_first', 'approvals_first'])->default('deadlines_first');
        });
        
        // Copiar os dados da coluna temporária para a nova
        DB::statement("UPDATE notification_settings SET priority_order = priority_order_temp");
        
        Schema::table('notification_settings', function (Blueprint $table) {
            // Remover a coluna temporária
            $table->dropColumn('priority_order_temp');
        });
    }
};
