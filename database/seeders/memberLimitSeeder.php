<?php

namespace Database\Seeders;

use App\Models\UserInvite\UserInvite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class memberLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $userInvites = UserInvite::all();

        foreach ($userInvites as $userInvite) {

            if (empty($userInvite['total_member_limit']))
            {
                $data = UserInvite::where('email', $userInvite['email'])->first();

                $data->update(['total_member_limit' => $userInvite['members_limit']]);
            }
        }
    }
}
