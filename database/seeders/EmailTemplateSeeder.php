<?php

namespace Database\Seeders;

use http\Url;
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

        $reset_password = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HumanOp</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="https://staging.humanop.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>
    <div style="padding: 20px; color: #333; background-color: #f3deba;">
        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Hi {$userName},</h1>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">You have requested us to send a link to reset your password for your HumanOp account. Click on the button below.</p>
        <div style="display: flex; justify-content: center !important;">
            <a href="{$link}" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Reset Password</a>
        </div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">If you did not initiate the request, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
    <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>. If you no longer want to receive mails from us, please ignore this email.
        </p>
    </div>
</div>
</body>
</html>';


        $email_verification = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HumanOp</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="{$logo}" alt="HumanOP Logo" style="height: 50px;">
    </div>
    <div style="padding: 20px; color: #333; background-color: #f3deba;">
        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Hi {$userName},</h1>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Welcome to HumanOp! To complete your account setup, please verify your email address by clicking the button below:</p>
        <div style="display: flex; justify-content: center;">
            <a href="{$link}" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Verify Your Email</a>
        </div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Once your account is verified, you will have full access to all our features and services. If you did not sign up for this account, please ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for choosing HumanOp. We look forward to you.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
    <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>. If you no longer want to receive mails from us.
        </p>
    </div>
</div>
</body>
</html>';


        $email_template = [
            ['name' => 'email-verification', 'format' => $email_verification],
            ['name' => 'reset-password', 'format' => $reset_password]
        ];

        DB::table('email_templates')->insert($email_template);
    }
}
