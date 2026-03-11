<?php

namespace App\Http\Controllers;
use App\Models\Perfume;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PerfumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::get('role') === '0'){
            $perfumesmen = Perfume::where('suit', 'men')->orderBy('name')->get();
            $perfumeswomen = Perfume::where('suit', 'women')->orderBy('name')->get();
            $perfumesunisex = Perfume::where('suit', 'unisex')->orderBy('name')->get();
            $perfumesgift = Perfume::where('suit', 'giftbox')->orderBy('name')->get();
            return view('fragrance')->with(['perfumesmen' => $perfumesmen, 
                                            'perfumeswomen'=>$perfumeswomen, 
                                            'perfumesunisex'=>$perfumesunisex, 
                                            'perfumesgift'=>$perfumesgift, 
                                        ]);
        }

        else if(Session::get('role') === '1'){
            $perfumes = Perfume::all();
            return view('perfume.perfume-list')->with(['perfumes' => $perfumes]);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        return view('perfume.add-perfume')->with('brands', $brands);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        // $accords = $request->input(['accord']); //array
        // if (empty($accords))
        // $accord = null;
        
        // else 
        // $accord = implode(', ', $accords);


        // return $accord; //string
        
        $perfume = new Perfume();
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rrp' => 'required',
            'rrp_myr' => 'required',
            'price' => 'required',
            'name' => 'required',
            'brand' => 'required',
            'size' => 'required',
            'year' => 'required',
            'quality' => 'required',
            'quality' => 'required',
            'accord' => 'required',
            'gender' => 'required',
            'season' => 'required',
            'time' => 'required',
            'percent_autumn' => 'required',
            'percent_spring' => 'required',
            'percent_summer' => 'required',
            'percent_winter' => 'required',
            'percent_day' => 'required',
            'percent_night' => 'required',
        ],
        [
        ]);
      
        $imageName = $request->input('brand').'_'.$request->input('name').'_'.$request->input('size').'.'.$request->image->extension();  
        $request->image->move(public_path('images/perfumes'), $imageName);

        $perfume->brand = $request->input('brand');
        $perfume->name = $request->input('name');
        $perfume->image = $imageName;
        $perfume->price = $request->input('price');
        $perfume->size = $request->input('size');
        $perfume->quality = $request->input('quality');
        $perfume->year = $request->input('year');
        $perfume->suit = $request->input('gender');
        $perfume->suit_season = $request->input('season');
        
        $perfume->percent_autumn = $request->input('percent_autumn');
        $perfume->percent_summer = $request->input('percent_summer');
        $perfume->percent_spring = $request->input('percent_spring');
        $perfume->percent_winter = $request->input('percent_winter');
        $perfume->percent_night = $request->input('percent_night');
        $perfume->percent_day = $request->input('percent_day');

        $perfume->suit_time = $request->input('time');
        $perfume->price = $request->input('price');
        $perfume->rrp = $request->input('rrp');
        $perfume->rrp_rm = $request->input('rrp_myr');
        $perfume->main_accord = $request->input('accord');
        $perfume->top_notes = $request->input('topnotes');
        $perfume->middle_notes = $request->input('middlenotes');
        $perfume->base_notes = $request->input('basenotes');
        $perfume->history = $request->input('history');
        $perfume->ref_shop = "Kedai Sebelah Tu";
        $perfume->variation_id = $request->input('brand').'_'.$request->input('name');


        $perfume->save();

        return redirect('/perfume')->with('success', 'Perfume has been added !');
    }   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $perfume = Perfume::find($id);
        return view('perfume.perfume-card')->with('perfume', $perfume);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perfume = Perfume::find($id);
        return view('perfume.edit-perfume')->with('perfume',$perfume);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $perfume = Perfume::find($id);
        $perfume->delete();
        return redirect('/perfume')->with('success', $perfume->brand.' '.$perfume->name. ' ('. $perfume->size.' ml) has been deleted');
    }
}
