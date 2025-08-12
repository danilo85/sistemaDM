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
        Schema::table('clientes', function (Blueprint $table) {
            // Adiciona a coluna user_id
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // Define um valor padrão para registros existentes (primeiro usuário do sistema)
        $firstUser = User::first();
        if ($firstUser) {
            DB::table('clientes')->whereNull('user_id')->update(['user_id' => $firstUser->id]);
        }

        // Remove a possibilidade de null após definir os valores
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
