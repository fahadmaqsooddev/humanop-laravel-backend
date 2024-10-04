<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class makeReferral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:referral';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::transaction(function(){
            $users = User::all();
                foreach ($users as $user) {
                        $referralCode = Str::random(5).$user->id.Str::random(5);
                        $user->update(['referral_code' => $referralCode]);
                }
            });
    }
}
