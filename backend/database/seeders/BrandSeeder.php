<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run()
    {
        DB::table('brand')->insert([
            [
                'code'       => 'CK',
                'name'       => 'Calvin Klein',
                'image'      => 'images/brands/Calvin Klein_CK.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'CN',
                'name'       => 'Chanel',
                'image'      => 'images/brands/Channel_CN.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'DR',
                'name'       => 'Dior',
                'image'      => 'images/brands/Dior_DR.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'WS',
                'name'       => 'Watsons',
                'image'      => 'images/brands/Watsons_WS.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'YSL',
                'name'       => 'Yves Saint Laurent',
                'image'      => 'images/brands/Yves Saint Laurent_YSL.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
