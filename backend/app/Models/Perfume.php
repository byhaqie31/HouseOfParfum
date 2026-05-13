<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfume extends Model
{
    use HasFactory;

    protected $table = 'perfume';

    /** @var list<string> */
    protected $fillable = [
        'brand',
        'name',
        'image',
        'price',
        'rrp',
        'rrp_rm',
        'size',
        'quality',
        'year',
        'suit',
        'suit_season',
        'suit_time',
        'main_accord',
        'top_notes',
        'middle_notes',
        'base_notes',
        'history',
        'ref_shop',
        'variation_id',
        'percent_summer',
        'percent_autumn',
        'percent_spring',
        'percent_winter',
        'percent_day',
        'percent_night',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'price' => 'float',
            'rrp' => 'float',
            'rrp_rm' => 'float',
            'size' => 'integer',
            'percent_summer' => 'integer',
            'percent_autumn' => 'integer',
            'percent_spring' => 'integer',
            'percent_winter' => 'integer',
            'percent_day' => 'integer',
            'percent_night' => 'integer',
        ];
    }
}
