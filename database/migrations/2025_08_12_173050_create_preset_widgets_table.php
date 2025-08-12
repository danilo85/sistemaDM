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
        Schema::create('preset_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preset_id')->constrained('dashboard_presets')->onDelete('cascade');
            $table->string('widget_type');
            $table->string('title');
            $table->string('size', 1)->default('M'); // S, M, L
            $table->integer('position_x');
            $table->integer('position_y');
            $table->integer('width');
            $table->integer('height');
            $table->boolean('is_pinned')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();
            
            $table->index(['preset_id', 'widget_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preset_widgets');
    }
};
