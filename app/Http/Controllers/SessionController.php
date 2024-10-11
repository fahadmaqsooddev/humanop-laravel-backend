<?php

namespace App\Http\Controllers;

use App\Enums\Admin\Admin;
use App\Helpers\Practitioner\PractitionerHelpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\HAIChai\HaiChat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        if (Auth::check())
        {
            if (Auth::user()['is_admin'] == '1')
            {
                return redirect()->route('admin_dashboard');
            }else{
                return redirect()->route('client_dashboard');
            }
        }else{
            return view('session/login');
        }

    }

    public function practitionerLogin($slug, $slug2)
    {
        try {

            $user = User::where('first_name', $slug)->where('last_name', $slug2)->where('is_admin', 4)->exists();

            if ($user)
            {
                return view('practitioner-dashboard/session/login', compact('slug', 'slug2'));
            }
            else
            {
                return redirect()->to('/' . $slug . '/' . $slug2 . '/login')->withErrors(['msgError' => 'This Practitioner does not exist']);
            }

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $attributes = request()->validate([
                'email'=>'required|email',
                'password'=>'required',
            ]);

            $attributes['status'] = 1;

            if(Auth::attempt($attributes))
            {
                if (isset($request['remember']) && !empty($request['remember']))
                {
                    setcookie("email", $attributes['email'], 30*time()+3600);
                    setcookie("password", $attributes['password'], 30*time()+3600);
                }else
                {
                    setcookie("email", "");
                    setcookie("password", "");
                }

                $user = Helpers::getWebUser();

                Session::forget('google_user'); // forget the session of the google data

                if ($user->is_admin == Admin::IS_CUSTOMER){

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

                    DailyTip::updateUserDailyTip();

                    ActionPlan::storeUserActionPlan();

                    User::updateUserIsFeedback();
                }

                if ($user->is_admin === Admin::IS_PRACTITIONER){

                    return redirect('/practitioner/dashboard');
                }

                return redirect()->route('admin_dashboard');
            }

            return back()->withErrors(['msgError' => 'These credentials do not match our records.']);

        }catch (ValidationException $validationException){

            return back()->with(['errors' => $validationException->validator->errors()]);

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function loginClientToPractitioner(Request $request)
    {
        try {

            $attributes = request()->validate([
                'email'=>'required|email',
                'password'=>'required',
            ]);

            $user = User::where('first_name', $request['first_name'])->where('last_name', $request['last_name'])->where('is_admin', 4)->exists();

            if ($user)
            {
                if(Auth::attempt($attributes))
                {
                    if (isset($request['remember']) && !empty($request['remember']))
                    {
                        setcookie("email", $attributes['email'], 30*time()+3600);
                        setcookie("password", $attributes['password'], 30*time()+3600);
                    }else
                    {
                        setcookie("email", "");
                        setcookie("password", "");
                    }

                    $user = Helpers::getWebUser();

                    Session::forget('google_user'); // forget the session of the google data

                    Helpers::createCustomerAndSubscriptionOnStripe($user);

                    DailyTip::updateUserDailyTip();

                    ActionPlan::storeUserActionPlan();

                    User::updateUserIsFeedback();

                    return redirect()->to(PractitionerHelpers::makePractitionerUrl('dashboard'));
                }

                return back()->withErrors(['msgError' => 'These credentials do not match our records.']);
            }

        }catch (ValidationException $validationException){

            return back()->with(['errors' => $validationException->validator->errors()]);

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function destroy()
    {

        HaiChat::deleteAdminChat(Cache::get('admin'));

        Auth::logout();

        Session::flush();

        Cache::forget('admin');

        return redirect('/')->with(['success'=>'You\'ve been logged out.']);
    }

    public static function destroyPractitioner()
    {

        HaiChat::deleteAdminChat(Cache::get('admin'));

        Auth::logout();

        Session::flush();

        Cache::forget('admin');

        return redirect()->to(PractitionerHelpers::makePractitionerUrl('login'))->with(['success'=>'You\'ve been logged out.']);
    }

    public function loginBackToAdmin()
    {

        $admin = Cache::get('admin');

        HaiChat::deleteAdminChat($admin);

        Auth::logout();

        if (($admin['is_admin'] ?? false) && ($admin['admin_id'] ?? null)){

            $admin_user = User::where('id', $admin['admin_id'])->first();

            Auth::login($admin_user);

            return redirect()->to('/admin/users');

        }else{

            return redirect()->to('/login');
        }

    }
}
