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
        Schema::table('clientes', function (Blueprint $table) {
            // Adicionamos a coluna, depois da coluna 'email'
            $table->boolean('is_complete')->default(false)->after('email');
        });

        Schema::table('autores', function (Blueprint $table) {
            // Fazemos o mesmo para autores, colocando depois de 'email'
            $table->boolean('is_complete')->default(false)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes_and_autores_tables', function (Blueprint $table) {
            //
        });
    }
};
