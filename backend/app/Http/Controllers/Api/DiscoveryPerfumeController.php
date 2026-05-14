<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscoveryPerfume;
use App\Support\DiscoveryQuery;
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
 *
 * The query construction lives in {@see DiscoveryQuery} — shared with
 * `PerfumeController`, which serves the same data in the legacy storefront shape.
 */
class DiscoveryPerfumeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            DiscoveryQuery::build($request)->paginate(DiscoveryQuery::perPage($request)),
        );
    }

    public function show($id)
    {
        return response()->json(DiscoveryPerfume::findOrFail($id));
    }
}
