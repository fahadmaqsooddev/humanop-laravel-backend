<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FamilyMatrixRelationshipController extends Controller
{


    public function familyMatrixrelationship()
    {
        try {

            return view('admin-dashboards.family-matrix-relationship.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
