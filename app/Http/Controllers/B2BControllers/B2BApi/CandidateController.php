<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\B2B\UserCandidateInvite;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {

        $this->middleware('auth:api');

        $this->user = $user;
    }

    public function createInviteLinkForCandidate(Request $request)
    {
        try {
            $email = $request->input('email');

            $checkInviteLink = UserInvite::getSingleInvite($email);

            if ($checkInviteLink) {

                $checkCompany = UserCandidateInvite::getSingleInvite($checkInviteLink->id);

                if ($checkCompany) {

                    return Helpers::successResponse("{$email} already has an invite link. Please create an account.");

                } else {

                    UserCandidateInvite::createUserInvite($checkInviteLink->id);

                    return Helpers::successResponse("{$email} invite link generated successfully.");
                }
            }

            $newInvite = UserInvite::createInvite($email);

            if ($newInvite) {

                UserCandidateInvite::createUserInvite($newInvite->id);

                return Helpers::successResponse("{$email} invite link generated successfully.");

            }

            return Helpers::serverErrorResponse("Failed to generate invite link for {$email}.");

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public static function allCandidates()
    {
        try {
            $invites = UserCandidateInvite::allCandidateInvites();

            $candidateInvites = [];

            foreach ($invites as $invite) {

                $companyName = $invite['user']['company_name'] ?? 'N/A';
                $inviteLink = $invite['inviteLinks']['link'] ?? 'N/A';

                $candidateInvites[] = [
                    'invite_link'   => config('client_url.client_dashboard_url') . '/register?link=' . $inviteLink . '&company_name=' . $companyName,
                    'email'         => $invite['user']['email'] ?? 'N/A',
                    'company_name'  => $companyName
                ];
            }

            return Helpers::successResponse("All candidate invites.", $candidateInvites);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

}
