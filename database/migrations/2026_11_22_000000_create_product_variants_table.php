<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Varian produk (kode/SKU, nama tampilan, gambar sendiri).
 * Data varian lama yang tersimpan sebagai teks di specifications['Varian (SKU)'] dipindahkan ke tabel ini.
 */
return new class extends Migration
{
    private const SPEC_KEY = 'Varian (SKU)';
    private const COUNT_KEY = 'Jumlah varian';

    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('code', 100);
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'code']);
            $table->index(['product_id', 'sort_order']);
        });

        // Pindahkan varian dari spesifikasi ke tabel baru
        $now = now();
        DB::table('products')->whereNotNull('specifications')->orderBy('id')->each(function ($product) use ($now) {
            $specs = json_decode($product->specifications, true);
            if (!is_array($specs) || empty($specs[self::SPEC_KEY])) {
                return;
            }

            $codes = array_values(array_unique(array_filter(array_map('trim', explode(',', $specs[self::SPEC_KEY])))));
            $rows = [];
            foreach ($codes as $i => $code) {
                $rows[] = ['product_id' => $product->id, 'code' => $code, 'sort_order' => $i + 1, 'created_at' => $now, 'updated_at' => $now];
            }
            DB::table('product_variants')->insert($rows);

            unset($specs[self::SPEC_KEY], $specs[self::COUNT_KEY]);
            DB::table('products')->where('id', $product->id)->update([
                'specifications' => $specs ? json_encode($specs, JSON_UNESCAPED_UNICODE) : null,
            ]);
        });
    }

    public function down(): void
    {
        // Kembalikan daftar kode varian ke spesifikasi sebelum tabel dihapus
        if (Schema::hasTable('product_variants')) {
            DB::table('product_variants')->orderBy('product_id')->orderBy('sort_order')->get()
                ->groupBy('product_id')
                ->each(function ($variants, $productId) {
                    $specs = json_decode((string) DB::table('products')->where('id', $productId)->value('specifications'), true) ?: [];
                    $specs[self::SPEC_KEY] = $variants->pluck('code')->implode(', ');
                    $specs[self::COUNT_KEY] = (string) $variants->count();
                    DB::table('products')->where('id', $productId)->update(['specifications' => json_encode($specs, JSON_UNESCAPED_UNICODE)]);
                });
        }

        Schema::dropIfExists('product_variants');
    }
};
