<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // The storefront now reads the discovery catalogue (`discovery_perfumes`,
        // populated by `php artisan discovery:import`), so the hardcoded 42-row
        // PerfumeSeeder and its BrandSeeder are no longer wired in. The seeder
        // files are kept on disk for reference; the `perfume`/`brand` tables are
        // left intact (never truncated).
    }
}
