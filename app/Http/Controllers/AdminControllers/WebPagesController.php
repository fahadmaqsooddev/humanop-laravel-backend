<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pages\Page;

class WebPagesController extends Controller
{
    protected $page = null;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function webPages()
    {
        try {

            $web_pages = Page::allPages();

            return view('admin-dashboards.web-pages.index', compact('web_pages'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_web_pages')->with('error', $exception->getMessage());

        }
    }

    public function editWebPages($id)
    {
        try {

            $web_page = Page::getSinglePage($id);

            return view('admin-dashboards.web-pages.edit', compact('web_page'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_web_pages')->with('error', $exception->getMessage());

        }
    }
}
