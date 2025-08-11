<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Desabilita temporariamente a verificação de chaves estrangeiras
        Schema::disableForeignKeyConstraints();

        // Adiciona a coluna user_id se ela ainda não existir
        if (!Schema::hasColumn('bancos', 'user_id')) {
            Schema::table('bancos', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // Encontra o primeiro usuário (admin) e atualiza os bancos existentes
        $firstUser = User::orderBy('id', 'asc')->first();
        if ($firstUser) {
            DB::table('bancos')->whereNull('user_id')->update(['user_id' => $firstUser->id]);
        }

        // Torna a coluna obrigatória e adiciona a chave estrangeira
        Schema::table('bancos', function (Blueprint $table) {
            if (DB::table('bancos')->whereNull('user_id')->doesntExist()) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Reabilita a verificação
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bancos', function (Blueprint $table) {
            if (Schema::hasColumn('bancos', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
