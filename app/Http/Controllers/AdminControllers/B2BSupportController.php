<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\B2B\B2BSupport;

class B2BSupportController extends Controller
{
    //

    public function b2bSupport()
    {
        try {

            $support = B2BSupport::AllSupport();
            // dd($support);
            return view('admin-dashboards/b2b/b2b_support', compact('support'));

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
    public function b2bSupportDetail($id)
    {
        try {

            $support = B2BSupport::singleSupport($id);
            // dd($support);
            return view('admin-dashboards/b2b/b2b_support_detail', compact('support'));

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }
}
