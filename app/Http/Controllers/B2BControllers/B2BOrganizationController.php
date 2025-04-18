<?php

namespace App\Http\Controllers\B2BControllers;

use App\Http\Controllers\Controller;
use App\Models\B2B\B2BBusinessCandidates;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class B2BOrganizationController extends Controller
{
    //
    public function allOrganizations(){
        try {

            return view('b2b-dashboard.b2b-organizations.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    // public function view(Request $request){
    //     try {
    //         $prefer = $request['prefer'];
    //         $userId = $request['user_id'];
    //        $users= B2BBusinessCandidates::getCandidatesMembers($userId,$prefer);            
          
    //         return view('b2b-dashboard.b2b-organizations.candidate-member-view',compact('users'));

    //     } catch (\Exception $exception) {

    //         return redirect()->back()->with('error', $exception->getMessage());

    //     }
    // }
}
