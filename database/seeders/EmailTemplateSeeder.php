<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->truncate();

        $email_verification = '
        <h3>Email Verification</h3>
    <p>Hi, {$userName}</p>
    <p>Thank you for registering with us!</p>
    <p>Please click the link below to verify your email address:</p>
    <a href="{$link}" target="_blank" style="color: #f2661c; font-weight: bold; font-size: 20px"> Verify Email</a>
    <p>If you did not create an account, please email support@humanop.com asap to make sure we address the error.</p>';

        $email_template = [
            ['name' => 'email-verification', 'format' => $email_verification],
        ];

        DB::table('email_templates')->insert($email_template);
    }
}
