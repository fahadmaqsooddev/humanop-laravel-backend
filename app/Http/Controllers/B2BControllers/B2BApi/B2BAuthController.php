<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\LoginRequest;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class B2BAuthController extends Controller
{

    protected $auth;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['loginB2b']);

        $this->auth = Auth::guard('api');
    }

}
