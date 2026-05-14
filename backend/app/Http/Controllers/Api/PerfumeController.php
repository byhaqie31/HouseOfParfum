<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscoveryPerfume;
use App\Models\Perfume;
use App\Support\DiscoveryQuery;
use App\Support\PerfumeTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Storefront perfume API.
 *
 * `index` / `show` read from the discovery catalogue (`discovery_perfumes`,
 * ~24k real fragrances) and transform each row into the legacy `CatalogPerfume`
 * shape the Nuxt frontend expects — see {@see PerfumeTransformer}. The legacy
 * `perfume` table is no longer the read source for the storefront.
 *
 * `store` / `update` / `destroy` are left on the `Perfume` model: they're the
 * auth-gated admin write paths and aren't reachable from the storefront UI.
 */
class PerfumeController extends Controller
{
    /**
     * Paginated catalogue. Accepts the same query params as `/api/discovery`
     * (search, brand, gender, note, accord, sort, direction, per_page) and
     * returns a paginator envelope of transformed perfumes.
     */
    public function index(Request $request)
    {
        $perfumes = DiscoveryQuery::build($request)
            ->paginate(DiscoveryQuery::perPage($request))
            ->through(fn (DiscoveryPerfume $d) => PerfumeTransformer::toCatalog($d));

        return response()->json($perfumes);
    }

    /**
     * Filter facets for the storefront's advanced filters — the distinct
     * brand list and the flattened note vocabulary. Cached for a day; the
     * catalogue only changes when `discovery:import` re-runs.
     */
    public function facets()
    {
        $facets = Cache::remember('discovery.facets', now()->addDay(), function () {
            $brands = DiscoveryPerfume::query()
                ->whereNotNull('brand')
                ->orderBy('brand')
                ->distinct()
                ->pluck('brand')
                ->values();

            // Flatten the three JSON pyramid columns into one sorted vocabulary.
            // Set-based dedup over a streamed cursor — O(n), no growing collections.
            $seen = [];
            foreach (
                DB::table('discovery_perfumes')
                    ->select('notes_top', 'notes_middle', 'notes_base')
                    ->cursor() as $row
            ) {
                foreach ([$row->notes_top, $row->notes_middle, $row->notes_base] as $json) {
                    if (! $json) {
                        continue;
                    }
                    foreach (json_decode($json, true) ?? [] as $note) {
                        $seen[$note] = true;
                    }
                }
            }
            $notes = array_keys($seen);
            sort($notes);

            return [
                'brands' => $brands,
                'notes'  => $notes,
            ];
        });

        return response()->json($facets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rrp'            => 'required',
            'rrp_myr'        => 'required',
            'price'          => 'required',
            'name'           => 'required',
            'brand'          => 'required',
            'size'           => 'required',
            'year'           => 'required',
            'quality'        => 'required',
            'accord'         => 'required',
            'gender'         => 'required',
            'season'         => 'required',
            'time'           => 'required',
            'percent_autumn' => 'required',
            'percent_spring' => 'required',
            'percent_summer' => 'required',
            'percent_winter' => 'required',
            'percent_day'    => 'required',
            'percent_night'  => 'required',
        ]);

        $imageName = $request->input('brand').'_'.$request->input('name').'_'.$request->input('size').'.'.$request->image->extension();
        $request->image->move(public_path('images/perfumes'), $imageName);

        $perfume = Perfume::create([
            'brand'          => $request->brand,
            'name'           => $request->name,
            'image'          => $imageName,
            'price'          => $request->price,
            'size'           => $request->size,
            'quality'        => $request->quality,
            'year'           => $request->year,
            'suit'           => $request->gender,
            'suit_season'    => $request->season,
            'suit_time'      => $request->time,
            'rrp'            => $request->rrp,
            'rrp_rm'         => $request->rrp_myr,
            'main_accord'    => $request->accord,
            'top_notes'      => $request->topnotes,
            'middle_notes'   => $request->middlenotes,
            'base_notes'     => $request->basenotes,
            'history'        => $request->history,
            'variation_id'   => $request->brand.'_'.$request->name,
            'percent_autumn' => $request->percent_autumn,
            'percent_summer' => $request->percent_summer,
            'percent_spring' => $request->percent_spring,
            'percent_winter' => $request->percent_winter,
            'percent_night'  => $request->percent_night,
            'percent_day'    => $request->percent_day,
            'ref_shop'       => 'Kedai Sebelah Tu',
        ]);

        return response()->json($perfume, 201);
    }

    public function show($id)
    {
        $perfume = DiscoveryPerfume::findOrFail($id);

        return response()->json(PerfumeTransformer::toCatalog($perfume));
    }

    public function update(Request $request, $id)
    {
        $perfume = Perfume::findOrFail($id);
        $perfume->update($request->all());
        return response()->json($perfume);
    }

    public function destroy($id)
    {
        $perfume = Perfume::findOrFail($id);
        $perfume->delete();
        return response()->json(['message' => 'Perfume deleted successfully']);
    }
}
