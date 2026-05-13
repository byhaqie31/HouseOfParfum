<?php

namespace App\Http\Controllers;
use App\Models\Perfume;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $autumn = Perfume::where('suit_season', 'autumn')->orderBy('name')->get();
        $winter = Perfume::where('suit_season', 'winter')->orderBy('name')->get();
        $spring = Perfume::where('suit_season', 'spring')->orderBy('name')->get();
        $summer = Perfume::where('suit_season', 'summer')->orderBy('name')->get();

        return view('season')->with(['autumn' => $autumn, 
                                    'winter'=>$winter, 
                                    'spring'=>$spring,
                                    'summer' =>$summer,  
                                    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('perfume.add-perfume');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
