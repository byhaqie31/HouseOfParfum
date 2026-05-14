<?php

namespace App\Support;

/**
 * Derives season / time-of-day wear profiles from a fragrance's accords.
 *
 * `discovery_perfumes` carries no seasonal-fit data, but the storefront's
 * "when it wears best" bars and the frontend scent-matcher both expect the
 * legacy `suit_season` / `suit_time` / `percent_*` fields. This heuristic
 * synthesises them from the `accords` array — the token directions mirror
 * `frontend/app/data/scent-matching.ts` ACCORD_DELTA so the two agree.
 *
 * Best-effort, not science: tune ACCORD_MAP and rebuild the image.
 */
class AccordProfile
{
    /**
     * Accord token => weight contributed to each season / time bucket.
     * Tokens are matched as substrings, so "warm spicy" picks up "spicy",
     * "white floral" picks up "floral", etc. Unmapped accords contribute 0.
     *
     * @var array<string, array<string, int>>
     */
    private const ACCORD_MAP = [
        // Fresh / bright — summer & spring, daytime
        'citrus'    => ['summer' => 3, 'spring' => 2, 'day' => 3],
        'aquatic'   => ['summer' => 3, 'spring' => 1, 'day' => 3],
        'marine'    => ['summer' => 3, 'spring' => 1, 'day' => 3],
        'ozonic'    => ['summer' => 2, 'spring' => 1, 'day' => 2],
        'fresh'     => ['summer' => 2, 'spring' => 2, 'day' => 2],
        'green'     => ['spring' => 3, 'summer' => 1, 'day' => 2],
        'herbal'    => ['spring' => 2, 'summer' => 1, 'day' => 1],
        'aromatic'  => ['spring' => 2, 'autumn' => 1, 'day' => 1],
        'lavender'  => ['spring' => 2, 'summer' => 1, 'day' => 1],
        'fruity'    => ['summer' => 2, 'spring' => 2, 'day' => 2],
        'tropical'  => ['summer' => 3, 'day' => 2],
        'coconut'   => ['summer' => 2, 'day' => 1],
        // Floral — mostly spring, daytime
        'floral'    => ['spring' => 3, 'summer' => 1, 'day' => 2],
        'rose'      => ['spring' => 2, 'autumn' => 1],
        'powdery'   => ['winter' => 2, 'autumn' => 1, 'night' => 1],
        'iris'      => ['winter' => 2, 'autumn' => 2],
        'tuberose'  => ['autumn' => 1, 'night' => 2],
        // Sweet / gourmand — autumn & winter, evening
        'sweet'     => ['autumn' => 2, 'winter' => 2, 'night' => 2],
        'gourmand'  => ['autumn' => 2, 'winter' => 3, 'night' => 3],
        'vanilla'   => ['autumn' => 2, 'winter' => 3, 'night' => 2],
        'caramel'   => ['autumn' => 2, 'winter' => 2, 'night' => 2],
        'honey'     => ['autumn' => 2, 'winter' => 1, 'night' => 1],
        'cacao'     => ['winter' => 3, 'night' => 2],
        'chocolate' => ['winter' => 3, 'night' => 2],
        'coffee'    => ['autumn' => 2, 'winter' => 2, 'night' => 2],
        'nutty'     => ['autumn' => 2, 'winter' => 2, 'night' => 1],
        // Spicy / warm — autumn & winter, evening
        'spicy'     => ['autumn' => 2, 'winter' => 3, 'night' => 2],
        'cinnamon'  => ['autumn' => 2, 'winter' => 2, 'night' => 1],
        // Amber / oriental — winter, night
        'amber'     => ['winter' => 3, 'autumn' => 2, 'night' => 3],
        'oriental'  => ['winter' => 3, 'night' => 3],
        'balsamic'  => ['winter' => 2, 'autumn' => 2, 'night' => 2],
        // Woody / dark — autumn & winter, night
        'woody'     => ['autumn' => 2, 'winter' => 3, 'night' => 2],
        'mossy'     => ['autumn' => 3, 'winter' => 1],
        'chypre'    => ['autumn' => 3, 'winter' => 1],
        'earthy'    => ['autumn' => 3, 'winter' => 1],
        'leather'   => ['winter' => 3, 'night' => 3],
        'oud'       => ['winter' => 3, 'night' => 3],
        'smoky'     => ['winter' => 3, 'night' => 3],
        'incense'   => ['winter' => 3, 'night' => 3],
        'tobacco'   => ['autumn' => 2, 'winter' => 3, 'night' => 3],
        'animalic'  => ['winter' => 2, 'night' => 3],
        'musky'     => ['autumn' => 1, 'winter' => 2, 'night' => 2],
    ];

    /** @var list<string> */
    private const SEASONS = ['summer', 'autumn', 'winter', 'spring'];

    /** @var list<string> */
    private const TIMES = ['day', 'night'];

    /**
     * @param  array<int, string>|null  $accords
     * @return array{
     *   percent_summer:int, percent_autumn:int, percent_winter:int, percent_spring:int,
     *   percent_day:int, percent_night:int, suit_season:?string, suit_time:?string
     * }
     */
    public static function forPerfume(?array $accords): array
    {
        $weights = array_fill_keys([...self::SEASONS, ...self::TIMES], 0);

        foreach ($accords ?? [] as $accord) {
            $token = mb_strtolower(trim((string) $accord));
            if ($token === '') {
                continue;
            }
            foreach (self::ACCORD_MAP as $key => $contrib) {
                if (str_contains($token, $key)) {
                    foreach ($contrib as $bucket => $weight) {
                        $weights[$bucket] += $weight;
                    }
                }
            }
        }

        $seasons = self::normalise($weights, self::SEASONS);
        $times = self::normalise($weights, self::TIMES);

        return [
            'percent_summer' => $seasons['summer'],
            'percent_autumn' => $seasons['autumn'],
            'percent_winter' => $seasons['winter'],
            'percent_spring' => $seasons['spring'],
            'percent_day'    => $times['day'],
            'percent_night'  => $times['night'],
            'suit_season'    => self::dominant($seasons),
            'suit_time'      => self::dominant($times),
        ];
    }

    /**
     * Scale a group of raw weights to 0–100 with the group's max at 100.
     * An all-zero group stays all-zero.
     *
     * @param  array<string, int>  $weights
     * @param  list<string>  $keys
     * @return array<string, int>
     */
    private static function normalise(array $weights, array $keys): array
    {
        $max = max(array_map(fn ($k) => $weights[$k], $keys));

        $out = [];
        foreach ($keys as $k) {
            $out[$k] = $max > 0 ? (int) round($weights[$k] / $max * 100) : 0;
        }

        return $out;
    }

    /**
     * Title-cased label of the highest-scoring bucket, or null if the group
     * is all-zero (so the UI hides the section / falls back to defaults).
     *
     * @param  array<string, int>  $scores
     */
    private static function dominant(array $scores): ?string
    {
        $max = max($scores);
        if ($max <= 0) {
            return null;
        }

        return ucfirst((string) array_search($max, $scores, true));
    }
}
