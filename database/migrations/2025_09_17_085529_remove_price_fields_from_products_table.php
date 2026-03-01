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
        Schema::table('products', function (Blueprint $table) {
            // Remove price-related fields that are not needed
            $table->dropColumn(['price', 'discount_price', 'sku', 'stock', 'brand']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hanya tambah kolom jika belum ada (aman saat state tidak konsisten / refresh).
            if (! Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 10, 2);
            }
            if (! Schema::hasColumn('products', 'discount_price')) {
                $table->decimal('discount_price', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable();
            }
            if (! Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0);
            }
            if (! Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable();
            }
        });
    }
};
