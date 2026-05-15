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
        'size',
        'acquired',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
