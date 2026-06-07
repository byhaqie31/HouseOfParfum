<?php

namespace App\Support;

use App\Models\DiscoveryPerfume;

/**
 * Maps a `discovery_perfumes` row into the storefront shape the Nuxt frontend
 * expects. Notes/accords are stored as JSON arrays and flattened to
 * comma-separated strings for the UI's split() parsers.
 */
class PerfumeTransformer
{
    /**
     * @return array<string, mixed>
     */
    public static function toCatalog(DiscoveryPerfume $d): array
    {
        return [
            'id'          => $d->id,
            'brand'       => $d->brand,
            'name'        => $d->name,
            'country'     => $d->country,
            'year'        => $d->release_year,
            'suit'        => $d->gender,

            // Editorial fields — null until filled in by an admin.
            'image'       => $d->image,
            'history'     => $d->history,
            // Curated scent family (drives the colour). Null until an admin sets it;
            // Discover falls back to deriving it from main_accord client-side.
            'family'      => $d->family,

            // Scent profile.
            'main_accord'  => self::commaList($d->accords),
            'top_notes'    => self::commaList($d->notes_top),
            'middle_notes' => self::commaList($d->notes_middle),
            'base_notes'   => self::commaList($d->notes_base),
            'perfumers'    => self::commaList($d->perfumers),

            // Wear profile — persisted columns derived by AccordProfile at import time.
            'suit_season'    => $d->suit_season,
            'suit_time'      => $d->suit_time,
            'percent_summer' => $d->percent_summer,
            'percent_autumn' => $d->percent_autumn,
            'percent_winter' => $d->percent_winter,
            'percent_spring' => $d->percent_spring,
            'percent_day'    => $d->percent_day,
            'percent_night'  => $d->percent_night,

            // Community data from Fragrantica.
            'rating'     => $d->rating,
            'votes'      => $d->votes,
            'source_url' => $d->source_url,
        ];
    }

    /**
     * JSON string array → ", "-joined string. Null/empty → null so templates'
     * v-if guards hide the section entirely.
     *
     * @param  array<int, string>|null  $items
     */
    private static function commaList(?array $items): ?string
    {
        return empty($items) ? null : implode(', ', $items);
    }
}
