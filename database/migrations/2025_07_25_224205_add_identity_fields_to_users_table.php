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
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna para o caminho da foto de perfil, após a coluna 'email'
            $table->string('profile_photo_path')->nullable()->after('email');
            
            // Adiciona a coluna para o caminho da logomarca
            $table->string('logo_path')->nullable()->after('profile_photo_path');
            
            // Adiciona a coluna para o caminho da assinatura digital
            $table->string('signature_path')->nullable()->after('logo_path');
            
            // Adiciona a coluna para o CPF ou CNPJ
            $table->string('cpf_cnpj')->nullable()->after('signature_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Define a ordem reversa para remoção, caso precise desfazer
            $table->dropColumn(['profile_photo_path', 'logo_path', 'signature_path', 'cpf_cnpj']);
        });
    }
};
