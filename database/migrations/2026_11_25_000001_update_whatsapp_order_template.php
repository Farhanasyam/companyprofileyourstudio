<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ganti template pesan order WA bawaan lama (beremoji) dengan template baru yang rapi tanpa emoji.
     * Template yang sudah diubah sendiri oleh admin tidak disentuh.
     */
    public function up(): void
    {
        $this->swap(Setting::WHATSAPP_ORDER_TEMPLATE_LEGACY, Setting::WHATSAPP_ORDER_TEMPLATE_DEFAULT);
    }

    public function down(): void
    {
        $this->swap(Setting::WHATSAPP_ORDER_TEMPLATE_DEFAULT, Setting::WHATSAPP_ORDER_TEMPLATE_LEGACY);
    }

    private function swap(string $from, string $to): void
    {
        $row = DB::table('settings')->where('key', 'whatsapp_order_template')->first();
        if (!$row) {
            return;
        }

        // Textarea admin menyimpan baris baru sebagai \r\n — samakan sebelum membandingkan
        $normalize = fn (?string $s) => trim(str_replace("\r\n", "\n", (string) $s));
        if ($normalize($row->value) === $normalize($from)) {
            DB::table('settings')->where('key', 'whatsapp_order_template')->update([
                'value' => $to,
                'updated_at' => now(),
            ]);
        }
    }
};
