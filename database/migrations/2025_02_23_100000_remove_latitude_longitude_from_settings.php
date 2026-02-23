<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations. Remove deprecated latitude/longitude keys from settings.
     * Map is now displayed via maps_iframe (HTML iframe embed) only.
     */
    public function up(): void
    {
        DB::table('settings')
            ->whereIn('key', [
                'maps_latitude',
                'maps_longitude',
                'company_latitude',
                'company_longitude',
            ])
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $rows = [
            ['key' => 'maps_latitude', 'value' => '-6.2087634', 'type' => 'text', 'group' => 'seo', 'description' => 'Business location latitude coordinate', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maps_longitude', 'value' => '106.8195613', 'type' => 'text', 'group' => 'seo', 'description' => 'Business location longitude coordinate', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_latitude', 'value' => '-6.2088', 'type' => 'text', 'group' => 'company', 'description' => 'Latitude untuk Google Maps', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_longitude', 'value' => '106.8456', 'type' => 'text', 'group' => 'company', 'description' => 'Longitude untuk Google Maps', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('settings')->insert($rows);
    }
};
