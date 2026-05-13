<?php

namespace App\Http\Controllers;
use App\Models\Perfume;
use Illuminate\Http\Request;

class TypeController extends Controller
{
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parfum = Perfume::where('quality', 'parfum')->orderBy('name')->get();
        $edp = Perfume::where('quality', 'edp')->orderBy('name')->get();
        $edt = Perfume::where('quality', 'edt')->orderBy('name')->get();
        $edc = Perfume::where('quality', 'edc')->orderBy('name')->get();

        return view('type')->with(['parfum' => $parfum, 
                                        'edp'=>$edp, 
                                        'edt'=>$edt, 
                                        'edc'=>$edc,  
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
