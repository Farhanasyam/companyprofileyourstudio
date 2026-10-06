<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'metode_pengiriman')) {
                $table->string('metode_pengiriman', 20)->default('kirim')->after('no_hp'); // kirim | ambil
            }
            if (!Schema::hasColumn('orders', 'alamat')) {
                $table->text('alamat')->nullable()->after('metode_pengiriman');
            }
            if (!Schema::hasColumn('orders', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('orders', 'kota')) {
                $table->string('kota')->nullable()->after('kecamatan');
            }
            if (!Schema::hasColumn('orders', 'kode_pos')) {
                $table->string('kode_pos', 10)->nullable()->after('kota');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['metode_pengiriman', 'alamat', 'kecamatan', 'kota', 'kode_pos'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
