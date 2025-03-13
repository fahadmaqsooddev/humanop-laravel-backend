<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInvite\UserInvite;
use App\Models\B2B\UserCandidateInvite;
use App\Models\B2B\B2BBusinessCandidates;
use Carbon\Carbon;

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
                $email = $invite['inviteLinks']['email'] ?? 'N/A';

                $candidateInvites[] = [
                    'invite_link'   => config('client_url.client_dashboard_url') . '/register?link=' . $inviteLink . '&company_name=' . $companyName,
                    'email'         => $email,
                    'company_name'  => $companyName
                ];
            }

            return Helpers::successResponse("All candidate invites.", $candidateInvites);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function getAllCandidates(){
        try {

            $candidates = B2BBusinessCandidates::allBusinessCandidates(Helpers::getUser()['id'])->map(function ($candidate) {

                $candidate->users->gender = $candidate->users->gender ==  Admin::IS_MALE ? 'Male' : 'Female';
                $candidate->users->status = $candidate->users->last_login ? 'on-board' : 'pending';
                
                $candidate->users->last_login = $candidate->users->last_login ? Carbon::parse($candidate->last_login)->format('m/d/Y h:i A') : null;
                

                return $candidate;

            });

            return Helpers::successResponse('All candidates', $candidates);


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function ConvertCandidate(Request $request){
        try {
            if(!empty($request->query('candidate_id'))){

               B2BBusinessCandidates::CandidatetoMember($request->query('candidate_id'));
                return Helpers::successResponse(' Candidate Change To Member');
            }else{
                return Helpers::serverErrorResponse("Failed to find candidate id");
            }
            


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function DeletesingleCandidate(Request $request){
        try {
            if(!empty($request->query('candidate_id'))){

               $candidate= B2BBusinessCandidates::DeletedCandidate($request->query('candidate_id'));
               
                return Helpers::successResponse(' Candidate Deleted Succesfully');
            }else{
                return Helpers::serverErrorResponse("Failed to find candidate id");
            }
            


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function ArchivesingleCandidate(Request $request){
        try {
            if(!empty($request->query('candidate_id'))){

               $candidate= B2BBusinessCandidates::ArchivedCandidate($request->query('candidate_id'));

                return Helpers::successResponse('  Candidate archive Succesfully');
            }else{
                return Helpers::serverErrorResponse("Failed to find candidate id");
            }
            


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function AllArchiveCandidates(Request $request){
        try {
           

               $archivecandidates= B2BBusinessCandidates::AllArchivedCandidates( Helpers::getUser()['id']);

                return Helpers::successResponse('Archive Candidates',$archivecandidates);
            
            


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function AllDeletedCandidates(Request $request){
        try {
           

               $deletedcandidates= B2BBusinessCandidates::AlldeletedCandidates( Helpers::getUser()['id']);

                return Helpers::successResponse('Deleted Candidates',$deletedcandidates);
            
            


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

}
