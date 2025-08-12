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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('days_after_view')->default(3);
            $table->integer('days_before_deadline')->default(5);
            $table->json('enabled_types')->nullable();
            $table->enum('priority_order', ['deadlines_first', 'approvals_first'])->default('deadlines_first');
            $table->timestamps();
            
            // Índice para performance
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};