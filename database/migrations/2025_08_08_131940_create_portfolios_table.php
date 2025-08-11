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
    Schema::create('portfolios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('orcamento_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('portfolio_category_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('description');
        $table->date('post_date');
        $table->string('external_link')->nullable();
        $table->string('link_legend')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
