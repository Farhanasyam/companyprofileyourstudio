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
        'kecamatan',
        'kota',
        'kode_pos',
        'catatan',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
