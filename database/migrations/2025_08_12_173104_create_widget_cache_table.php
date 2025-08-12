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
        Schema::create('widget_cache', function (Blueprint $table) {
            $table->id();
            $table->string('cache_key')->unique();
            $table->string('widget_type');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('data'); // Cached widget data
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['widget_type', 'user_id']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_cache');
    }
};
