<?php

namespace App\Http\Controllers;

use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
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

            return view('practitioner-dashboard/session/login');

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

                return redirect()->route('admin_dashboard');
            }

            return back()->withErrors(['msgError' => 'These credentials do not match our records.']);

        }catch (ValidationException $validationException){

            return back()->with(['errors' => $validationException->validator->errors()]);

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function practitionerStore(Request $request)
    {
        try {
            $attributes = request()->validate([
                'email'=>'required|email',
                'password'=>'required',
            ]);

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

                return redirect()->route('admin_dashboard');
            }

            return back()->withErrors(['msgError' => 'These credentials do not match our records.']);

        }catch (ValidationException $validationException){

            return back()->with(['errors' => $validationException->validator->errors()]);

        } catch (\Exception $exception) {

            return back()->withErrors(['msgError' => $exception->getMessage()]);
        }
    }

    public function destroy()
    {

        Auth::logout();

        return redirect('/login')->with(['success'=>'You\'ve been logged out.']);
    }
}
