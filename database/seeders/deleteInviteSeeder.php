<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInvite\UserInvite;
use App\Models\UserInvite\UserInviteLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class deleteInviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::where('step', 3)->get();

//        $getInvite = UserInvite::all();
//
//        dd(count($getInvite));

        foreach ($users as $user) {

            $getInvite = UserInvite::where('email', $user->email)->first();

            if ($getInvite) {

                UserInviteLog::where('invite_id', $getInvite->id)->delete();

                $getInvite->delete();

            }

        }
    }
}
