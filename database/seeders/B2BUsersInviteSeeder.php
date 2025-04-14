<?php

namespace Database\Seeders;

use App\Models\B2B\UserCandidateInvite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class B2BUsersInviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $allDatas = UserCandidateInvite::where('role', null)->get();


        foreach ($allDatas as $data) {

            $data->update(['role' => 1]);
        }
    }
}
