<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'code',
        'name',
        'image',
        'sort_order',
    ];

    protected static function booted()
    {
        // Modal order & kartu produk menyimpan data varian di cache
        static::saved(fn () => Product::clearPublicCaches());
        static::deleted(function (ProductVariant $variant) {
            if ($variant->image) {
                ImageHelper::delete($variant->image);
            }
            Product::clearPublicCaches();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /** Nama yang ditampilkan ke pembeli: nama varian bila diisi, selain itu kodenya */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->code;
    }

    /** URL gambar varian (relatif, ter-encode seperti gambar produk) atau null */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        $encoded = ImageHelper::encodePathForUrl($this->image);
        return $encoded !== '' ? '/storage/' . $encoded : null;
    }
}
