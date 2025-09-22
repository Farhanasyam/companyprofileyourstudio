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
        // Add indexes for better performance
        Schema::table('events', function (Blueprint $table) {
            $table->index(['is_active', 'start_date']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'is_featured']);
            $table->index(['category_id', 'is_active']);
            $table->index(['sort_order', 'is_active']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'status']);
            $table->index(['published_at', 'status']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'start_date']);
            $table->dropIndex(['is_featured', 'is_active']);
            $table->dropIndex(['start_date', 'end_date']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['category_id', 'is_active']);
            $table->dropIndex(['sort_order', 'is_active']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
            $table->dropIndex(['is_featured', 'status']);
            $table->dropIndex(['published_at', 'status']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'sort_order']);
        });
    }
};
