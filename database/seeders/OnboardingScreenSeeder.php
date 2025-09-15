<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OnboardingScreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('signup_screens')->truncate();

        $screens = [
            ['screen_name' => 'Welcome to HumanOp', 'description' => '<p><span style="white-space-collapse: preserve;">The most powerful and objective human optimization tools on the planet today</span></p><p><span style="white-space-collapse: preserve;">This Is Your Window To Your Unique Operating Manual of Self... Let is Get You Started!</span></p><p><span style="white-space-collapse: preserve;">What is your name?</span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['screen_name' => 'Signup First Step', 'description' => '<p><span style="white-space-collapse: preserve;">So excited to be able to give you access to the HumanOp technology. Since this will be your own PRIVATE account to access today is and all future updates to your understanding of self....</span></p><p><span style="white-space-collapse: preserve;">Please let us know your FAVORITE way to log in:</span><span data-metadata="&lt;!--(figmeta)eyJmaWxlS2V5IjoiQnhPdkRTbUIwMXZkWTh6cTZuSzNTcyIsInBhc3RlSUQiOjE0MTY3MzU5OTAsImRhdGFUeXBlIjoic2NlbmUifQo=(/figmeta)--&gt;"></span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['screen_name' => 'Email Verification Step', 'description' => '<p><span style="white-space-collapse: preserve;"><b><span data-metadata="&lt;!--(figmeta)eyJmaWxlS2V5IjoiQnhPdkRTbUIwMXZkWTh6cTZuSzNTcyIsInBhc3RlSUQiOjE0MTY3MzU5OTAsImRhdGFUeXBlIjoic2NlbmUifQo=(/figmeta)--&gt;"></span>Great! </b></span></p><p><span style="white-space-collapse: preserve;"><b>Please check your email inbox to confirm the registration of your account. I will wait...</b></span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['screen_name' => 'Phone Number Step', 'description' => '<p><span style="white-space-collapse: preserve;"><span data-metadata="&lt;!--(figmeta)eyJmaWxlS2V5IjoiQnhPdkRTbUIwMXZkWTh6cTZuSzNTcyIsInBhc3RlSUQiOjE0MTY3MzU5OTAsImRhdGFUeXBlIjoic2NlbmUifQo=(/figmeta)--&gt;"></span>Well that was easy!</span></p><p><span style="white-space-collapse: preserve;"> Just remember to log in the same way again next time. 😊 </span></p><p><span style="white-space-collapse: preserve;">For an extra layer of security, please enter your mobile number below only if you want SMS notifications and authorizations. </span></p><p><span style="white-space-collapse: preserve;">This is optional</span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['screen_name' => 'Date of Birth Step', 'description' => '<p><span style="white-space-collapse: preserve;"><span data-metadata="&lt;!--(figmeta)eyJmaWxlS2V5IjoiQnhPdkRTbUIwMXZkWTh6cTZuSzNTcyIsInBhc3RlSUQiOjE0MTY3MzU5OTAsImRhdGFUeXBlIjoic2NlbmUifQo=(/figmeta)--&gt;"></span>The next few questions are REALLY important to helping you get the most out of your HumanOp Assessment Results. </span></p><p><span style="white-space-collapse: preserve;">Every human being has a unique way to stay optmized in different intervals of life. </span></p><p><span style="white-space-collapse: preserve;">Knowing your birthdate will help us give you the best strategies for the current interval of life you are in. </span></p><p><span style="white-space-collapse: preserve;">What is your date of birth?.</span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['screen_name' => 'Gender Step', 'description' => '<p><span style="white-space-collapse: preserve;"><span data-metadata="&lt;!--(figmeta)eyJmaWxlS2V5IjoiQnhPdkRTbUIwMXZkWTh6cTZuSzNTcyIsInBhc3RlSUQiOjE0MTY3MzU5OTAsImRhdGFUeXBlIjoic2NlbmUifQo=(/figmeta)--&gt;"></span>There are different questions for different genetic chromosomes. </span></p><p><span style="white-space-collapse: preserve;">To make sure you get the correct questions for accurate results,</span></p><p><span style="white-space-collapse: preserve;"> What was your gender at birth?</span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['screen_name' => 'Timezone Step', 'description' => '<p><span style="white-space-collapse: preserve;"><span data-metadata="&lt;!--(figmeta)eyJmaWxlS2V5IjoiQnhPdkRTbUIwMXZkWTh6cTZuSzNTcyIsInBhc3RlSUQiOjE0MTY3MzU5OTAsImRhdGFUeXBlIjoic2NlbmUifQo=(/figmeta)--&gt;"></span>One more question before we take you to the full assessment! </span></p><p><span style="white-space-collapse: preserve;">There are different strategies for different times of the day. </span></p><p><span style="white-space-collapse: preserve;">To provide more accurate time of day strategies...</span></p><p><span style="white-space-collapse: preserve;"> What timezone do you primarily live in?</span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['screen_name' => 'FInal Step', 'description' => '<p><span style="white-space-collapse: preserve;"><span data-metadata="&lt;!--(figmeta)eyJmaWxlS2V5IjoiQnhPdkRTbUIwMXZkWTh6cTZuSzNTcyIsInBhc3RlSUQiOjE0MTY3MzU5OTAsImRhdGFUeXBlIjoic2NlbmUifQo=(/figmeta)--&gt;"></span>This is great! </span></p><p><span style="white-space-collapse: preserve;">We have all the information to put together the most appropriate version of our assessment technology for you. </span></p><p><span style="white-space-collapse: preserve;">To get the most out of the app, we need to make sure you have at least ONE assessment result in the books. </span></p><p><span style="white-space-collapse: preserve;">It will take about 10-15 minutes, so make sure you are in a calm and relaxed state with no distractions around you. </span></p><p><span style="white-space-collapse: preserve;">You are 100% worth creating the time and space to unlock your greatest potential...see you inside!</span></p>','created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::transaction(function()use ($screens){

            foreach($screens as $screen){

                DB::table("signup_screens")->insert($screen);

            }

        });

    }
}
