<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscoveryPerfume;
use Illuminate\Http\Request;

/**
 * Read-only API over the discovery catalogue (~24k fragrances).
 *
 * The index is always paginated — the table is far too large to return whole —
 * and supports search, brand/gender filters, JSON note/accord filters, and a
 * whitelisted sort. Mutations are intentionally absent: the catalogue is
 * populated only by `php artisan discovery:import`.
 *
 * Query params on index:
 *   search     free text matched against name + brand
 *   brand      exact brand match
 *   gender     exact gender match (men | unisex | women)
 *   note       fragrance note — matched inside notes_top/middle/base
 *   accord     main accord — matched inside the accords array
 *   sort       rating | votes | release_year | name   (default: rating)
 *   direction  asc | desc                             (default: desc)
 *   per_page   1–100                                  (default: 24)
 */
class DiscoveryPerfumeController extends Controller
{
    /** Columns allowed in ?sort= — guards against arbitrary-column ordering. */
    private const SORTABLE = ['rating', 'votes', 'release_year', 'name'];

    public function index(Request $request)
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

        // Cap per_page so a client can't pull the whole table in one request.
        $perPage = min(max((int) $request->query('per_page', 24), 1), 100);

        return response()->json($query->paginate($perPage));
    }

    public function show($id)
    {
        return response()->json(DiscoveryPerfume::findOrFail($id));
    }
}
