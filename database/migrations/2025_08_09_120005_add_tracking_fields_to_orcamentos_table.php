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
        Schema::table('orcamentos', function (Blueprint $table) {
            if (!Schema::hasColumn('orcamentos', 'first_viewed_at')) {
                $table->timestamp('first_viewed_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orcamentos', 'last_viewed_at')) {
                $table->timestamp('last_viewed_at')->nullable()->after('first_viewed_at');
            }
            if (!Schema::hasColumn('orcamentos', 'view_count')) {
                $table->integer('view_count')->default(0)->after('last_viewed_at');
            }
        });
        
        // Adicionar índices separadamente
        Schema::table('orcamentos', function (Blueprint $table) {
            if (!Schema::hasIndex('orcamentos', 'orcamentos_first_viewed_at_index')) {
                $table->index(['first_viewed_at']);
            }
            if (!Schema::hasIndex('orcamentos', 'orcamentos_data_validade_index')) {
                $table->index(['data_validade']);
            }
            if (!Schema::hasIndex('orcamentos', 'orcamentos_status_index')) {
                $table->index(['status']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropIndex(['first_viewed_at']);
            $table->dropIndex(['data_validade']);
            $table->dropIndex(['status']);
            $table->dropColumn(['first_viewed_at', 'last_viewed_at', 'view_count']);
        });
    }
};