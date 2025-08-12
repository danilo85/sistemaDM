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
        Schema::table('notification_settings', function (Blueprint $table) {
            // Primeiro, remover a constraint do enum atual
            $table->dropColumn('priority_order');
        });
        
        Schema::table('notification_settings', function (Blueprint $table) {
            // Adicionar o campo novamente com os novos valores do enum
            $table->enum('priority_order', ['high_first', 'low_first', 'chronological'])->default('high_first');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            // Reverter para os valores antigos do enum
            $table->dropColumn('priority_order');
        });
        
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->enum('priority_order', ['deadlines_first', 'approvals_first'])->default('deadlines_first');
        });
    }
};
