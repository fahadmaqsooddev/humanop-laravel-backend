<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\AnnouncementNews\AnnouncementNews;

class AnnouncementsNewsController extends Controller
{

    protected $announcement_news = null;

    public function __construct(AnnouncementNews $announcement_news)
    {
        $this->announcement_news = $announcement_news;
    }

    public function announcementsNews()
    {
        try {

            return view('admin-dashboards.announcement-news.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
