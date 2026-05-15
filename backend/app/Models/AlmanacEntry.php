<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlmanacEntry extends Model
{
    protected $fillable = ['chapter_id', 'question', 'answer', 'sort_order'];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(AlmanacChapter::class, 'chapter_id');
    }
}
