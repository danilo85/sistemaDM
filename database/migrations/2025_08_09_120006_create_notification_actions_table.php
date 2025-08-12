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
        Schema::create('notification_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->onDelete('cascade');
            $table->enum('action_type', ['mark_read', 'snooze', 'open_budget', 'send_reminder']);
            $table->json('action_data')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Índices para performance
            $table->index(['notification_id']);
            $table->index(['action_type']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_actions');
    }
};