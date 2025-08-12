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
        Schema::create('trabalho_comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabalho_id')->constrained('trabalhos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->text('comentario');
            $table->boolean('visivel_cliente')->default(false); // Se o cliente pode ver este comentário
            $table->boolean('importante')->default(false); // Comentário importante/destacado
            
            $table->timestamps();
            
            // Índices para performance
            $table->index('trabalho_id');
            $table->index('created_at');
            $table->index(['trabalho_id', 'visivel_cliente']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabalho_comentarios');
    }
};