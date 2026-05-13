<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Idempotent: truncate the brand table (FK checks disabled briefly) and
     * insert the full catalogue. Safe to re-run via `php artisan db:seed`.
     */
    public function run()
    {
        // delete() is used instead of truncate() because TRUNCATE inside the
        // Docker MySQL container was silently no-op'ing (entries accumulating
        // each re-run). DELETE is reliable; brand has no FKs pointing in.
        DB::table('brand')->delete();

        $now = now();

        // image is '' (not null) because the migration's `->nullabe()` typo
        // landed the column as NOT NULL — null would fail the insert.
        DB::table('brand')->insert([
            ['code' => 'CK',  'name' => 'Calvin Klein',          'image' => 'images/brands/Calvin Klein_CK.jpg',         'created_at' => $now, 'updated_at' => $now],
            ['code' => 'CN',  'name' => 'Chanel',                'image' => 'images/brands/Channel_CN.png',              'created_at' => $now, 'updated_at' => $now],
            ['code' => 'DR',  'name' => 'Dior',                  'image' => 'images/brands/Dior_DR.jpg',                 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'WS',  'name' => 'Watsons',               'image' => 'images/brands/Watsons_WS.png',              'created_at' => $now, 'updated_at' => $now],
            ['code' => 'YSL', 'name' => 'Yves Saint Laurent',    'image' => 'images/brands/Yves Saint Laurent_YSL.jpg',  'created_at' => $now, 'updated_at' => $now],

            // — Editorial-luxe houses (no logo files yet; BottleIcon falls back gracefully) —
            ['code' => 'LL',  'name' => 'Le Labo',               'image' => '', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'AS',  'name' => 'Aesop',                 'image' => '', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'DP',  'name' => 'Diptyque',              'image' => '', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JM',  'name' => 'Jo Malone London',      'image' => '', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'TF',  'name' => 'Tom Ford',              'image' => '', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BR',  'name' => 'Byredo',                'image' => '', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MM',  'name' => 'Maison Margiela',       'image' => '', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'FM',  'name' => 'Frederic Malle',        'image' => '', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
