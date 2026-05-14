<?php

namespace App\Support;

use App\Models\DiscoveryPerfume;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Shared query construction for the discovery catalogue — the search / filter /
 * sort logic behind both `DiscoveryPerfumeController` (raw discovery shape) and
 * `PerfumeController` (transformed into the legacy storefront shape).
 *
 * Supported query params: search, brand, gender, note, accord, sort, direction,
 * per_page. See `DiscoveryPerfumeController`'s docblock for the full contract.
 */
class DiscoveryQuery
{
    /** Columns allowed in ?sort= — guards against arbitrary-column ordering. */
    private const SORTABLE = ['rating', 'votes', 'release_year', 'name'];

    public static function build(Request $request): Builder
    {
        $query = DiscoveryPerfume::query();

        // Free-text search across name + brand.
        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Exact-match column filters.
        if ($brand = $request->query('brand')) {
            $query->where('brand', $brand);
        }
        if ($gender = $request->query('gender')) {
            $query->where('gender', $gender);
        }

        // JSON-array membership filters. Stored note/accord values are
        // lower-case, so the needle is lower-cased to match.
        if ($note = $request->query('note')) {
            $note = mb_strtolower($note);
            $query->where(function ($q) use ($note) {
                $q->whereJsonContains('notes_top', $note)
                    ->orWhereJsonContains('notes_middle', $note)
                    ->orWhereJsonContains('notes_base', $note);
            });
        }
        if ($accord = $request->query('accord')) {
            $query->whereJsonContains('accords', mb_strtolower($accord));
        }

        // Whitelisted sort; defaults to highest-rated first.
        $sort = in_array($request->query('sort'), self::SORTABLE, true)
            ? $request->query('sort')
            : 'rating';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);

        return $query;
    }

    /** Per-page size, clamped to 1–100 so a client can't pull the whole table. */
    public static function perPage(Request $request, int $default = 24): int
    {
        return min(max((int) $request->query('per_page', $default), 1), 100);
    }
}
