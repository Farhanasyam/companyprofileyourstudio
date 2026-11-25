<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected static ?array $valueCache = null;

    /**
     * Template bawaan pesan order WhatsApp (tanpa emoji; format tebal WhatsApp *...*).
     * {items} & {pengiriman} disusun oleh public/js/site.js.
     */
    public const WHATSAPP_ORDER_TEMPLATE_DEFAULT = "Halo *{company_name}*,\nsaya ingin memesan produk berikut:\n\n*DAFTAR PESANAN*\n{items}\n\n*DATA PEMESAN*\nNama: {nama_pemesan}\nNo. WhatsApp: {no_hp}\n\n*PENGIRIMAN*\n{pengiriman}\n\n*CATATAN*\n{catatan}\n\nMohon konfirmasi ketersediaan barang dan total pembayarannya. Terima kasih.";

    /** Template bawaan versi lama (beremoji) — diganti otomatis oleh migrasi bila belum diubah admin. */
    public const WHATSAPP_ORDER_TEMPLATE_LEGACY = "Halo! 🙏\n\nSaya ingin memesan dari *{company_name}*:\n\n📦 *Daftar Pesanan:*\n{items}\n\n👤 *Pemesan:* {nama_pemesan}\n📱 *No. WA:* {no_hp}\n📝 *Catatan:* {catatan}\n\nTerima kasih. Salam kreatif! ✨";

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    // Static methods for easy access
    public static function get($key, $default = null)
    {
        static::loadValueCache();

        return static::$valueCache[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        static::loadValueCache();
        static::$valueCache[$key] = $value;

        return $setting;
    }

    public static function getGroup($group)
    {
        return static::where('group', $group)->pluck('value', 'key');
    }

    protected static function loadValueCache(): void
    {
        if (static::$valueCache === null) {
            static::$valueCache = static::query()->pluck('value', 'key')->all();
        }
    }

    // Scope
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
