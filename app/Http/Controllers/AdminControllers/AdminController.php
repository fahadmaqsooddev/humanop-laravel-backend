<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            return view('admin-dashboards.default');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function cms()
    {
        try {

            return view('admin-dashboards.cms');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }

    public function project()
    {
        try {

            return view('admin-dashboards.admin_projects');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function setting()
    {
        try {

            return view('admin-dashboards.setting');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function pagesUsersNewUser()
    {
        try {

            return view('admin-dashboards.new-user');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function pagesUsersReports()
    {
        try {

            return view('admin-dashboards.reports');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function answer()
    {
        try {

            return view('admin-dashboards.answer');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function grid()
    {
        try {

            return view('admin-dashboards.grid');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function haiChat()
    {
        try {

            return view('admin-dashboards.hai-chat');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function userInfo()
    {
        try {

            return view('admin-dashboards.user_info');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function userDetail()
    {
        try {

            return view('admin-dashboards.user_detail');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
    public function allUsers()
    {
        try {

            return view('admin-dashboards.all_users');

        }catch (\Exception $exception)
        {

            return redirect()->route('admin_dashboard')->with('error', $exception->getMessage());

        }
    }
}
