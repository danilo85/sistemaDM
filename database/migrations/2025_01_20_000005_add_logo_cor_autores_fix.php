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
            if (!Schema::hasColumn('autores', 'logo')) {
                $table->string('logo')->nullable();
            }
            if (!Schema::hasColumn('autores', 'cor')) {
                $table->string('cor', 7)->default('#ffffff');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('autores', function (Blueprint $table) {
            if (Schema::hasColumn('autores', 'logo')) {
                $table->dropColumn('logo');
            }
            if (Schema::hasColumn('autores', 'cor')) {
                $table->dropColumn('cor');
            }
        });
    }
};