<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data wilayah administrasi Indonesia (Kepmendagri No 300.2.2-2138 Tahun 2025) + kode pos per desa/kelurahan,
     * untuk dropdown berjenjang Provinsi → Kota/Kab → Kecamatan → Kelurahan di form pesanan.
     *
     * Sumber: github.com/cahyadsn/wilayah & github.com/cahyadsn/wilayah_kodepos (MIT License, (c) cahya dsn),
     * diringkas menjadi database/data/wilayah.csv.gz.
     * Kode: 35 (provinsi) → 35.07 (kab/kota) → 35.07.21 (kecamatan) → 35.07.21.2001 (kelurahan/desa).
     */
    public function up(): void
    {
        if (!Schema::hasTable('wilayah')) {
            Schema::create('wilayah', function (Blueprint $table) {
                $table->string('kode', 13)->primary();
                $table->string('nama', 100);
                $table->string('induk', 8)->nullable()->index(); // kode wilayah satu tingkat di atasnya
                $table->char('kodepos', 5)->nullable();
            });
        }

        if (!Schema::hasColumn('orders', 'kode_wilayah')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('kode_wilayah', 13)->nullable()->after('kode_pos');
            });
        }

        $this->import();
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'kode_wilayah')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('kode_wilayah');
            });
        }
        Schema::dropIfExists('wilayah');
    }

    private function import(): void
    {
        $path = database_path('data/wilayah.csv.gz');
        $handle = fopen('compress.zlib://' . $path, 'r');
        if ($handle === false) {
            throw new RuntimeException("Tidak dapat membaca data wilayah: {$path}");
        }

        DB::table('wilayah')->delete();
        fgetcsv($handle); // header: kode,nama,kodepos

        $batch = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2 || $row[0] === '') {
                continue;
            }
            [$kode, $nama] = $row;
            $cut = strrpos($kode, '.');
            $batch[] = [
                'kode' => $kode,
                'nama' => $nama,
                'induk' => $cut === false ? null : substr($kode, 0, $cut),
                'kodepos' => ($row[2] ?? '') !== '' ? $row[2] : null,
            ];
            if (count($batch) === 1000) {
                DB::table('wilayah')->insert($batch);
                $batch = [];
            }
        }
        if ($batch) {
            DB::table('wilayah')->insert($batch);
        }
        fclose($handle);
    }
};
