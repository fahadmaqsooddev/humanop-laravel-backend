<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\Admin\Admin;
use App\Models\Client\Point\Point;

class AddPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $users = User::where('is_admin', Admin::IS_CUSTOMER)
                     ->orWhere('is_admin', Admin::IS_B2B)
                     ->get();

        foreach ($users as $user) {
            
            if (Point::where('user_id', $user->id)->exists()) {
                continue; 
            }

            
            Point::create([
                'user_id' => $user->id,
                'point' => 50,
            ]);
        }


    }
}
