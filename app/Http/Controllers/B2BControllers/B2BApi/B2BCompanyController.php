<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\User;
use Illuminate\Http\Request;

class B2BCompanyController extends Controller
{
    // protected $user;

    // public function __construct(User $user)
    // {

    //     $this->middleware('auth:api');

    //     $this->user = $user;
    // }

    public static function allCompanies()
    {
        try {

            $companies = User::allCompanies();

            return Helpers::successResponse("All Companies Information", $companies);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    // public static function candidateSelectedCompanies()
    // {
    //     try {

    //         $companies = B2BBusinessCandidates::getCandidateBusiness();

    //         return Helpers::successResponse("All Companies Information", $companies);

    //     } catch (\Exception $exception) {
    //         return Helpers::serverErrorResponse($exception->getMessage());
    //     }
    // }

}
