<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'wardrobe_item_id',
        'brand',
        'name',
        'worn_at',
        'experience',
        'compliments',
        'longevity',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'worn_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
