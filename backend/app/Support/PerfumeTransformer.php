<?php

namespace App\Support;

use App\Models\DiscoveryPerfume;

/**
 * Maps a `discovery_perfumes` row into the legacy storefront `CatalogPerfume`
 * shape the Nuxt frontend expects (see `frontend/app/data/scent-matching.ts`).
 *
 * The discovery table stores notes/accords as JSON arrays; this flattens them
 * to the comma-separated strings the UI's `split(',')` parsers expect. The
 * season/time fields are read straight from the persisted wear-profile columns
 * (derived by {@see AccordProfile} at import time). `image` / `size` / `quality`
 * / `history` have no discovery equivalent and are returned null — every
 * frontend template v-if-guards them.
 */
class PerfumeTransformer
{
    /**
     * @return array<string, mixed>
     */
    public static function toCatalog(DiscoveryPerfume $d): array
    {
        return [
            'id'             => $d->id,
            'brand'          => $d->brand,
            'name'           => $d->name,
            'main_accord'    => self::commaList($d->accords),
            'top_notes'      => self::commaList($d->notes_top),
            'middle_notes'   => self::commaList($d->notes_middle),
            'base_notes'     => self::commaList($d->notes_base),
            'year'           => $d->release_year,
            'suit'           => $d->gender,
            // Derived wear profile — persisted columns (see AccordProfile + the importer).
            'suit_season'    => $d->suit_season,
            'suit_time'      => $d->suit_time,
            'percent_summer' => $d->percent_summer,
            'percent_autumn' => $d->percent_autumn,
            'percent_winter' => $d->percent_winter,
            'percent_spring' => $d->percent_spring,
            'percent_day'    => $d->percent_day,
            'percent_night'  => $d->percent_night,
            // No discovery equivalent — frontend templates guard these.
            'image'   => null,
            'size'    => null,
            'quality' => null,
            'history' => null,
            // Discovery extras — harmless passthrough; the frontend ignores unknown keys.
            'rating' => $d->rating,
            'votes'  => $d->votes,
        ];
    }

    /**
     * JSON string array -> ", "-joined string (the shape the UI's `split(',')`
     * parsers expect). Null / empty -> null so templates' v-if guards hide it.
     *
     * @param  array<int, string>|null  $items
     */
    private static function commaList(?array $items): ?string
    {
        return empty($items) ? null : implode(', ', $items);
    }
}
