<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlmanacChapter extends Model
{
    protected $fillable = ['slug', 'number', 'title', 'intro', 'sort_order'];

    public function entries(): HasMany
    {
        return $this->hasMany(AlmanacEntry::class, 'chapter_id')->orderBy('sort_order');
    }
}
