<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kelurahan & provinsi (isi otomatis dari titik lokasi) + titik lokasi pengiriman
     * (GPS pembeli / pin di peta) untuk link Google Maps.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'kelurahan')) {
                $table->string('kelurahan')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('orders', 'provinsi')) {
                $table->string('provinsi')->nullable()->after('kota');
            }
            if (!Schema::hasColumn('orders', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('kode_pos');
            }
            if (!Schema::hasColumn('orders', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['kelurahan', 'provinsi', 'latitude', 'longitude'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
