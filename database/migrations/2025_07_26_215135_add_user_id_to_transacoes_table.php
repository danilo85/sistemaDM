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
        // Desabilita a verificação de chaves estrangeiras temporariamente
        Schema::disableForeignKeyConstraints();

        // Passo 1: Adiciona a coluna se ela não existir.
        if (!Schema::hasColumn('transacoes', 'user_id')) {
            Schema::table('transacoes', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // Passo 2: Encontra o primeiro usuário e atualiza as transações existentes.
        $firstUser = User::orderBy('id', 'asc')->first();
        if ($firstUser) {
            DB::table('transacoes')->whereNull('user_id')->update(['user_id' => $firstUser->id]);
        }

        // Passo 3: Torna a coluna não nula (obrigatória).
        Schema::table('transacoes', function (Blueprint $table) {
            if (DB::table('transacoes')->whereNull('user_id')->doesntExist()) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
        });

        // Passo 4: Adiciona a chave estrangeira.
        Schema::table('transacoes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Reabilita a verificação de chaves estrangeiras
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacoes', function (Blueprint $table) {
            if (Schema::hasColumn('transacoes', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
