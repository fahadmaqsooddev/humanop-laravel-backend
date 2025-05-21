<?php

namespace App\Console\Commands;

use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\UserCandidateInvite;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Illuminate\Console\Command;

class DeleteB2BCandidateMemberInvite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:b2bCandidateMemberInvitesDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete B2B Candidate and Members Invite';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $data = B2BBusinessCandidates::allUser();

        foreach ($data as $user) {

            $matchedUser = User::where('id', $user['candidate_id'])->where('step',3)->first();

            if ($matchedUser) {

               $getInvite=UserInvite::getSingleInvite($matchedUser['email']);

               if($getInvite){

                UserCandidateInvite::deleteInvite($getInvite['id']);

               }

            }

        }

    }
}
