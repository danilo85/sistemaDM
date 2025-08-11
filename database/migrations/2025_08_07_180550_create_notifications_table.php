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
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary(); // Usa UUID para um ID único
                $table->string('type');
                $table->morphs('notifiable'); // Para o usuário que recebe a notificação
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

};
