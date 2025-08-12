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
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('budget_id')->nullable()->constrained('orcamentos')->onDelete('cascade');
            $table->string('type', 50); // budget_approved, budget_rejected, deadline_near, viewed_no_action
            $table->string('title');
            $table->text('message');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_read')->default(false);
            $table->timestamp('snooze_until')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Índices para performance
            $table->index(['user_id', 'is_read']);
            $table->index(['type']);
            $table->index(['priority']);
            $table->index(['created_at']);
            $table->index(['snooze_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};