<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemesan',
        'no_hp',
        'metode_pengiriman',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'kode_wilayah',
        'latitude',
        'longitude',
        'catatan',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Link Google Maps ke titik lokasi pengiriman (null bila pembeli tidak menandai lokasi).
     */
    public function getMapsUrlAttribute(): ?string
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . $this->latitude . ',' . $this->longitude;
    }
}
