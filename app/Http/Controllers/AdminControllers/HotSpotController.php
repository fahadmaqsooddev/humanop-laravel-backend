<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotSpot;
class HotSpotController extends Controller
{

    public function HotSpots(){
        try {
            return view('admin-dashboards.hotspots.index');

        } catch (\Exception $exception) {

            return redirect()->route('admin_hotspots')->with('error', $exception->getMessage());

        }
    }

    public function editHotSpot($id){
        try {

            $hotspot = Hotspot::find($id);
            return view('admin-dashboards.hotspots.edit',compact('hotspot'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_hotspots')->with('error', $exception->getMessage());

        }
    }


}
