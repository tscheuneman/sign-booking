<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Category;
use App\Location;
use App\Building;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = Asset::with('location.building', 'category', 'location.region')->paginate(50);
        return view('admin.assets',
            [
                'assets' => $assets
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cat = Category::get();
        return view('admin.assetCreate',
            [
                'categories' => $cat
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asset = new Asset();
        $location = new Location();



        $this->validate(request(), [
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'building' => 'required|integer|exists:buildings,id',
            'regionID' => 'numeric|required|exists:campuses,id',
            'name' => 'required',
            'category' => 'required|integer',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'color' => 'nullable',
            'material' => 'nullable',
            'image' => 'required|image',
            'specs' => 'json'
        ]);

        $specs = request('specs');

        $path = $request->file('image')->store(
            'images/', 'public'
        );

        $specification = $specs;

        $location->longitude = request('longitude');
        $location->latitude = request('latitude');
        $location->building_id = request('building');
        $location->asset_id = 0;
        $location->region_id = request('regionID');
        $location->save();
        $locationID = $location->id;


        $asset->cat_id = request('category');
        $asset->name = request('name');
        $asset->location_id = $locationID;
        $asset->specifications = $specification;
        $asset->latest_image = $path;
        $asset->save();
        $assetID = $asset->id;

        $location->asset_id = $assetID;
        $location->save();

        return redirect('/admin/assets');


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
