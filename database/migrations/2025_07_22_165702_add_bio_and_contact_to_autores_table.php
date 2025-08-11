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
        Schema::table('autores', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('email'); // 'text' para biografias mais longas
            $table->string('contact')->nullable()->after('bio'); // para um contato geral
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('autores', function (Blueprint $table) {
            $table->dropColumn(['bio', 'contact']);
        });
    }
};
