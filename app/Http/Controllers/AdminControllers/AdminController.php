<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Admin\Coupon\Coupon;
use App\Http\Requests\Admin\StripeSetting\UpdateStripeRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentDetail;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $stripe = null;

    public function __construct(StripeSetting $stripe)
    {
        $this->genre = $stripe;
    }

    public function index()
    {
        try {

            return view('admin-dashboards.default');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function cms()
    {
        try {

            return view('admin-dashboards.cms');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function project()
    {
        try {

            return view('admin-dashboards.admin_projects');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function setting()
    {
        try {

            $account = StripeSetting::getSingle();
            $currentUser = Auth::user();

            return view('admin-dashboards.setting', compact('account', 'currentUser'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function stripeSetting(UpdateStripeRequest $request, $id)
    {
        try {
            $dataArray = $request->only($this->genre->getFillable());

            StripeSetting::updateStripeAccount($dataArray, $id);

            return redirect()->route('admin_setting')->with('success', 'Stripe Account Update Successfully');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function pagesUsersNewUser()
    {
        try {

            return view('admin-dashboards.new-user');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function pagesUsersReports()
    {
        try {

            return view('admin-dashboards.reports');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userAnswer($id)
    {
        try {

            $assessment_details = AssessmentDetail::getDetail($id);

            return view('admin-dashboards.user.answer', compact('assessment_details'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function grid()
    {
        try {

            return view('admin-dashboards.grid');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function report($id)
    {
        try {

            $reports = Assessment::getReport($id);
            
            return view('admin-dashboards.user.user_report', compact('reports', 'id'));

        }catch (\Exception $exception)
        {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function haiChat()
    {
        try {

            return view('admin-dashboards.hai-chat');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userInfo($id)
    {
        try {
            $user = User::getSingleUser($id);
            return view('admin-dashboards.user.user_info', compact('user'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function userDetail($id)
    {
        try {

            return view('admin-dashboards.user.user_detail', compact('id'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function allUsers()
    {
        try {
            $assessments = Assessment::allAssessment();

            return view('admin-dashboards.user.all_users', compact('assessments'));
        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
    public function allAdmins()
    {

        try {
            $admins = User::allSubAdmin();
            return view('admin-dashboards.all_admins', compact('admins'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function allQuestions()
    {
        try {

            return view('admin-dashboards.all_questions');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

    public function abandonedAssessment()
    {
        try {

            $assessments = Assessment::abandonedAssessment();

            return view('admin-dashboards.user.abandoned_assessment', compact('assessments'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
