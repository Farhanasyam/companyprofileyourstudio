<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    /**
     * Daftar wilayah satu tingkat di bawah $kode (tanpa kode = daftar provinsi), urut nama.
     * Dipakai dropdown berjenjang di form pesanan. Data jarang berubah → boleh di-cache browser.
     */
    public function children(?string $kode = null)
    {
        $rows = DB::table('wilayah')
            ->when($kode === null, fn ($q) => $q->whereNull('induk'), fn ($q) => $q->where('induk', $kode))
            ->orderBy('nama')
            ->get(['kode', 'nama', 'kodepos'])
            ->map(function ($r) {
                $item = ['kode' => $r->kode, 'nama' => $r->nama];
                if ($r->kodepos) {
                    $item['kodepos'] = $r->kodepos;
                }
                return $item;
            });

        return response()->json($rows)->header('Cache-Control', 'public, max-age=604800');
    }

    /**
     * Cari kecamatan & kelurahan di dalam satu kab/kota dari nama-nama hasil peta (OpenStreetMap),
     * untuk titik yang data kecamatannya tidak lengkap di peta. Kode pos dipakai untuk membedakan
     * desa bernama sama. Hanya mengembalikan hasil bila tunggal (tidak menebak).
     *
     * GET /wilayah-cocokkan?kab=35.07[&kec=35.07.13]&nama[]=Banurejo&nama[]=Kepanjen[&kodepos=65163]
     */
    public function match(Request $request)
    {
        $kab = (string) $request->query('kab');
        $kec = (string) $request->query('kec', '');
        $kodepos = (string) $request->query('kodepos', '');
        if (!preg_match('/^\d{2}\.\d{2}$/', $kab)
            || ($kec !== '' && (!preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $kec) || !str_starts_with($kec, $kab . '.')))) {
            return response()->json(['message' => 'Kode wilayah tidak valid.'], 422);
        }

        // Urutan nama = prioritas (paling spesifik dulu, mis. desa sebelum kecamatan)
        $names = collect((array) $request->query('nama', []))
            ->filter(fn ($n) => is_string($n))
            ->map(fn ($n) => mb_strtolower(trim($n)))
            ->filter(fn ($n) => $n !== '' && mb_strlen($n) <= 100)
            ->unique()->take(8)->values();
        $empty = response()->json(['kecamatan' => null, 'kelurahan' => null]);
        if ($names->isEmpty()) {
            return $empty;
        }

        $kecamatan = $kec !== ''
            ? DB::table('wilayah')->where('kode', $kec)->pluck('nama', 'kode')
            : DB::table('wilayah')->where('induk', $kab)->whereIn(DB::raw('LOWER(nama)'), $names)->pluck('nama', 'kode');
        $kecamatanCodes = $kecamatan->keys();

        // Nama yang sudah dipakai sebagai nama kecamatan tidak dipakai lagi sebagai nama desa
        // (mis. peta: city="Kepanjen" = kecamatan; desa "Kepanjen" di dalamnya belum tentu titik pembeli)
        $kecamatanNames = $kecamatan->values()->map(fn ($n) => mb_strtolower($n))->all();
        $villageNames = $names->reject(fn ($n) => in_array($n, $kecamatanNames, true))->values();

        $villages = $villageNames->isEmpty() ? collect() : DB::table('wilayah')
            ->where('kode', 'like', ($kec !== '' ? $kec : $kab) . '.%')
            ->whereRaw('LENGTH(kode) = 13')
            ->whereIn(DB::raw('LOWER(nama)'), $villageNames)
            ->get(['kode', 'nama', 'induk', 'kodepos']);

        // Persempit: desa di kecamatan yang namanya juga muncul, lalu yang kode posnya sama
        if ($kecamatanCodes->isNotEmpty()) {
            $narrow = $villages->whereIn('induk', $kecamatanCodes->all());
            if ($narrow->isNotEmpty()) {
                $villages = $narrow;
            }
        }
        if (preg_match('/^\d{5}$/', $kodepos)) {
            $narrow = $villages->where('kodepos', $kodepos);
            if ($narrow->isNotEmpty()) {
                $villages = $narrow;
            }
        }

        foreach ($villageNames as $name) {
            $hits = $villages->filter(fn ($v) => mb_strtolower($v->nama) === $name);
            if ($hits->count() === 1) {
                $v = $hits->first();
                return response()->json(['kecamatan' => $v->induk, 'kelurahan' => $v->kode])
                    ->header('Cache-Control', 'public, max-age=86400');
            }
        }

        // Desa tidak pasti, tapi kecamatan tunggal → setidaknya kecamatan terpilih
        if ($kec === '' && $kecamatanCodes->count() === 1) {
            return response()->json(['kecamatan' => $kecamatanCodes->first(), 'kelurahan' => null])
                ->header('Cache-Control', 'public, max-age=86400');
        }

        return $empty;
    }

    /**
     * Nama provinsi, kota/kab, kecamatan & kelurahan dari kode kelurahan/desa (format 35.07.21.2001).
     * Mengembalikan null bila kode tidak valid / tidak lengkap.
     */
    public static function resolveVillage(string $kode): ?array
    {
        if (!preg_match('/^(\d{2})\.(\d{2})\.(\d{2})\.(\d{4})$/', $kode, $m)) {
            return null;
        }
        $codes = [
            'provinsi' => $m[1],
            'kota' => "{$m[1]}.{$m[2]}",
            'kecamatan' => "{$m[1]}.{$m[2]}.{$m[3]}",
            'kelurahan' => $kode,
        ];
        $names = DB::table('wilayah')->whereIn('kode', array_values($codes))->pluck('nama', 'kode');
        if ($names->count() !== 4) {
            return null;
        }

        return array_map(fn ($c) => $names[$c], $codes);
    }
}
