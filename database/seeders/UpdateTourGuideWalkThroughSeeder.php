<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateTourGuideWalkThroughSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users=User::all();
        foreach($users as $user){
         $user->update([
            'complete_tutorial'=>1,
            'complete_assessment_walkthrough'=>1
         ]);
        }
    }
}
