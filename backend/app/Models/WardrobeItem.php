<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WardrobeItem extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'catalog_id',
        'brand',
        'name',
        'family',
        'tagline',
        'concentration',
        'notes_top',
        'notes_heart',
        'notes_base',
        'size',
        'acquired',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'notes_top' => 'array',
            'notes_heart' => 'array',
            'notes_base' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
