<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingsSeeder::class,
            SeoSettingsSeeder::class,
            CategoriesSeeder::class,
            EventSeeder::class,
            EventDataSeeder::class,
            AboutUsSeeder::class,
            MultilingualDataSeeder::class,
            ProductListSeeder::class,
            UpdateProductUrlsSeeder::class,
        ]);
    }
}
