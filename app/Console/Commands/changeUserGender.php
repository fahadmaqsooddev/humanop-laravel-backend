<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class changeUserGender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:gender';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changing Male From 2 to 0 In DB Also Changing 0 to 2 for both' ;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

//        DB::table('questions')
//            ->whereIn('gender', [0, 2])
//            ->update([
//                'gender' => DB::raw('CASE WHEN gender = 2 THEN 0 WHEN gender = 0 THEN 2 END')
//            ]);


//        User::where('gender',2)->update(['gender' => 0]);

    }
}
