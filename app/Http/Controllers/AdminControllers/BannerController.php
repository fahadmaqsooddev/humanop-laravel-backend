<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\LifeTimeDeal\LifetimeDealBanner;

class BannerController extends Controller
{

    public function lifetimeDealBanner()
    {
        try {

            $banner = LifetimeDealBanner::latest()->first();

            return view('admin-dashboards.banners.lifetime_deal', ['banner' => $banner]);

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
