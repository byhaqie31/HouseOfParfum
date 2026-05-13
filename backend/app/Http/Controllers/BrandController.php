<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        return view('perfume.brand-list')->with('brands', $brands);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validator
        $this->validate($request,[
            
            'name'=>'required',
            'code'=> 'required',
        ],
        [
            'name.required' => 'Brand name is required.',
            'code.required' => 'Brand code is required.',
        ]);

        $brand = new Brand();
        $brand->name = $request->input('name');
        $brand->code = $request->input('code');
        
        if($request->hasFile('image')){
            $imageName = $request->input('name').'_'.$request->input('code').'.'.$request->image->extension();  
            $request->image->move(public_path('images/brands'), $imageName);
            $brand->image = $imageName;
        }
        $brand->save();

        return back()->with('success', $brand->name. ' ('. $brand->code.') brand has been added');
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
        $brand = Brand::find($id);
        $brand->delete();
        return back()->with('success', $brand->name. ' ('. $brand->code.') has been deleted');
    }
}
