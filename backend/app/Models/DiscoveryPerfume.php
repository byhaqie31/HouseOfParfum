<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A fragrance in the discovery catalogue — reference data imported from the
 * Fragrantica fra_cleaned.csv export via `php artisan discovery:import`.
 *
 * Read-only as far as the API is concerned; rows are written only by the
 * import command (which uses the query builder, not this model). Table name
 * `discovery_perfumes` is the Laravel-default plural of the class.
 */
class DiscoveryPerfume extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'source_id', 'source_url', 'name', 'brand', 'country', 'gender',
        'rating', 'votes', 'release_year',
        'notes_top', 'notes_middle', 'notes_base', 'accords', 'perfumers',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'rating'       => 'float',
            'votes'        => 'integer',
            'release_year' => 'integer',
            'notes_top'    => 'array',
            'notes_middle' => 'array',
            'notes_base'   => 'array',
            'accords'      => 'array',
            'perfumers'    => 'array',
        ];
    }
}
