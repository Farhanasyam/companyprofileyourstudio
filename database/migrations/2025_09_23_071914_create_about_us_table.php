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
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('section')->unique(); // hero, history, vision, mission, why_choose_us, contact_info
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // untuk why choose us section
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
