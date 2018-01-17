<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Category;
use App\Location;
use App\Building;
use File;

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

        \Session::flash('flash_created',request('name') . ' has been created');
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
        $asset = Asset::with('location.building', 'category', 'location.region')->where('id', '=', $id)->first();
        $cat = Category::get();
        return view('admin.assetEdit',
            [
                'categories' => $cat,
                'asset' => $asset
            ]
        );
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
        $this->validate(request(), [
            'id' => 'required|exists:assets',
            'loc_id' => 'required|exists:locations,id',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'building' => 'required|integer|exists:buildings,id',
            'regionID' => 'numeric|required|exists:campuses,id',
            'name' => 'required',
            'category' => 'required|integer',
            'image' => 'image',
            'specs' => 'json'
        ]);

        $specs = request('specs');

        if(request('image') != null) {
           $path = $request->file('image')->store(
               'images/', 'public'
           );
        }

        $specification = $specs;

        $asset = Asset::find($id);
        $location = Location::find(request('loc_id'));




        $location->longitude = request('longitude');
        $location->latitude = request('latitude');
        $location->building_id = request('building');
        $location->asset_id = $id;
        $location->region_id = request('regionID');
        $location->save();
        $locationID = $location->id;


        $asset->cat_id = request('category');
        $asset->name = request('name');
        $asset->location_id = $locationID;
        $asset->specifications = $specification;
        if(request('image') != null) {
            File::delete(public_path(). '/storage/' .$asset->latest_image);
         $asset->latest_image = $path;
        }
        $asset->save();

        \Session::flash('flash_created',request('name') . ' has been edited');
        return redirect('/admin/assets');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset = Asset::find($id);
            $asset_name = $asset->name;
        $location = Location::find($asset->location_id);

        File::delete(public_path(). '/storage/' .$asset->latest_image);
        $asset->delete();
        $location->delete();

        \Session::flash('flash_deleted',$asset_name . ' has been deleted');
        return redirect('/admin/assets');

    }
}
