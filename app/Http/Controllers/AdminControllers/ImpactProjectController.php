<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImpactProjectController extends Controller
{
   public function impactProjects(){
      try {
            return view('admin-dashboards.impact-project.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
   }
}
