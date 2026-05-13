<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perfume;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    public function index()
    {
        return response()->json(Perfume::all());
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
        $perfume = Perfume::findOrFail($id);
        return response()->json($perfume);
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
