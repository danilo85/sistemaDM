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
            // CORREÇÃO: Adiciona os campos após a coluna 'cpf_cnpj' que já existe
            $table->string('whatsapp')->nullable()->after('cpf_cnpj');
            $table->string('contact_email')->nullable()->after('whatsapp');
            $table->string('website_url')->nullable()->after('contact_email');
            $table->string('behance_url')->nullable()->after('website_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'contact_email', 'website_url', 'behance_url']);
        });
    }
};
