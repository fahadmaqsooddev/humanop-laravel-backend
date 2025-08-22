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
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>
    <div style="padding: 20px; color: #333; background-color: #f3deba;">
        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Hi {$userName},</h1>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
        <div style="display: flex; justify-content: center !important;">
            <a href="{$link}" target="_blank" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Reset Password</a>
        </div>
        </div>
        <div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">If you did not initiate the request, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>

    </div>
    <div style="padding: 20px; color: #333; background-color: #f3f2f7;">
{$body}
</div>

    <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
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
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>
    <div style="padding: 20px; color: #333; background-color: #f3deba;">
        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Hi {$userName},</h1>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
        <div style="display: flex; justify-content: center;">
            <a href="{$link}" target="_blank" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Verify Your Email</a>
        </div>

    </div>
    <div style="padding: 20px; color: #333; background-color: #f3f2f7;">
{$body}
</div>
        <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>

</div>
</body>
</html>';

        $b2b_email_verification = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HumanOp</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>
    <div style="padding: 20px; color: #333; background-color: #eaf3ff;">
        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Hi {$userName},</h1>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
        <div style="display: flex; justify-content: center;">
            <a href="{$link}" target="_blank" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Verify Your Email</a>
        </div>

    </div>

    <div style="padding: 20px; color: #333; background-color: #f3f2f7;">
{$body}
</div>
        <div style="text-align: center; padding: 20px; background-color: #eaf3ff; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>

</div>
</body>
</html>';


        $fa_verifiction_code = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HumanOp</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>
   <div style="padding: 20px; color: #333; background-color: #f3deba;">
        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Hi {$userName},</h1>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
        <div style="display: flex; justify-content: center;">
            <p style="color: #003a6d;font-size: 36px;background-color: darkgray;padding: 10px;border-radius: 10px;font-weight: bold; margin-right: 5px">{$code}</p>
        </div>

    </div>

    <div style="padding: 20px; color: #333; background-color: #f3f2f7;">
{$body}
</div>
            <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>

</div>
</body>
</html>';


        $b2b_signup_link = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HumanOp</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>

   <div style="padding: 20px; color: #333; background-color: #f3deba;">

        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
      <div style="display: flex; justify-content: center;">
            <a href="{$link}" target="_blank" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Goto Signup</a>
        </div>

    </div>

    <div style="padding: 20px; color: #333; background-color: #f3deba;">
{$body}
</div>
            <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>

</div>
</body>
</html>';

        $b2b_login_link = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HumanOp</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>

   <div style="padding: 20px; color: #333; background-color: #f3deba;">

        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
      <div style="display: flex; justify-content: center;">
            <a href="{$link}" target="_blank" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Goto Login</a>
        </div>

        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Email: {$email},</h1>
        <h1 style="font-size: 24px; color: #333; margin-bottom: 16px;">Password: {$password},</h1>

    </div>
    <div style="padding: 20px; color: #333; background-color: #f3f2f7;">
{$body}
</div>
    <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>

</div>
</body>
</html>';

        $maestro_signup = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>

   <div style="padding: 20px; color: #333; background-color: #f3f2f7;">

        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
      <div style="display: flex; justify-content: center;">
            <a href="{$link}" target="_blank" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Goto Signup</a>
        </div>

    </div>

<div style="padding: 20px; color: #333; background-color: #f3f2f7;">
{$body}
</div>
            <div style="text-align: center; padding: 20px; background-color: #f3f2f7; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 Maestro HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the Maestro HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>

</div>
</body>
</html>';

        $b2c_signup = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f8f8;">
<div style="margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div style="background-color: #003a6d; padding: 20px; text-align: center;">
        <img src="https://staging.humanoptech.com/assets/logos/HumanOp%20Logo.png" alt="HumanOP Logo" style="height: 50px;">
    </div>

   <div style="padding: 20px; color: #333; background-color: #f3deba;">

        <p style="margin: 12px 0; line-height: 1.5; color: black;">{$subject}</p>
      <div style="display: flex; justify-content: center;">
            <a href="{$link}" target="_blank" style="display: inline-block; margin: 20px 0; padding: 12px 20px; background-color: #003a6d; color: #fff; text-decoration: none; border-radius: 20px; font-size: 16px;">Goto Signup</a>
        </div>

    </div>

<div style="padding: 20px; color: #333; background-color: #f3deba;">
{$body}
</div>
            <div style="text-align: center; padding: 20px; background-color: #f3deba; color: black;">
        <p style="font-size: 14px; line-height: 1.5; margin: 8px 0;">&copy; 2024 HumanOP. All rights reserved.</p>
        <p style="font-size: 12px; line-height: 1.5; margin: 8px 0; color: black;">
            You are receiving this mail because you registered to join the HumanOP platform. This also shows
            that you agree to our <a href="{$service}" style="color: #003a6d; text-decoration: none;">Terms of Service</a> and <a
                href="{$privacy}" style="color: #003a6d; text-decoration: none;">Privacy Policy</a>
        </p>
    </div>

</div>
</body>
</html>';

        $body_reset_password = '<div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">If you did not initiate the request, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
    ';
        $body_reset_password_subject = 'You have requested us to send a link to reset your password for your HumanOp account. Click on the button below.';


        $body_email_verified = '<div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Once your account is verified, you will have full access to all our features and services. If you did not sign up for this account, please ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for choosing HumanOp. We look forward to you.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
    ';

        $email_verified_subject = 'Welcome to HumanOp! To complete your account setup, please verify your email address by clicking the button below:';

        $body_b2b_email_verified = '<div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Once your account is verified, you will have full access to all our features and services. If you did not sign up for this account, please ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for choosing HumanOp. We look forward to you.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
    ';
        $body_b2b_email_verified_subject = 'Welcome to Maestro HumanOp! To complete your account setup, please verify your email address by clicking the button below:';

        $body_fa_verification_code = '<div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> Please enter this code on the verification page to log in to your account. Once verified, you will gain full access to all our features and services.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> If you didn’t create a HumanOp account, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for joining HumanOp. We’re excited to have you on board!</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
    ';
        $subject_fa_verification_code = 'Welcome to HumanOp! Please verify your email address by entering the following verification code:';


        $b2b_signup_link_body = '<div>

        <p style="margin: 12px 0; line-height: 1.5; color: black;">  Once verified, you will gain full access to all our features and services.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> If you didn’t create a HumanOp account, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for joining HumanOp. We’re excited to have you on board!</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
';
        $b2b_signup_link_subject = 'Welcome to HumanOp! Please complete your signup by clicking the link below:';

        $b2b_login_link_body = '<div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">  Once verified, you will gain full access to all our features and services.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> If you didn’t create a HumanOp account, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for joining HumanOp. We’re excited to have you on board!</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
';
        $b2b_login_link_subject = 'Welcome to HumanOp! Please complete your signup by clicking the link below:';

        $b2b_maestro_signup_body = '<div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">  Once verified, you will gain full access to all our features and services.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> If you didn’t create a Maestro HumanOp account, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for joining Maestro HumanOp. We’re excited to have you on board!</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>Maestro HumanOP Team</p>
    </div>
';
        $b2c_signup_body = '<div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">  Once verified, you will gain full access to all our features and services.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> If you didn’t create a HumanOp account, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for joining HumanOp. We’re excited to have you on board!</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
    </div>
';
        $b2b_maestro_signup_subject = 'Welcome to Maestro HumanOp! Please complete your signup by clicking the link below:';

        $b2c_signup_subject = 'Welcome to HumanOp! Please complete your signup by clicking the link below:';


        $email_template = [
            ['name' => 'Verify Your Email Address', 'format' => $email_verification, 'body' => $body_email_verified, 'tag' => 'verified_email', 'type' => 1, 'subject' => $email_verified_subject],
            ['name' => 'reset-password', 'format' => $reset_password, 'body' => $body_reset_password, 'tag' => 'reset_password', 'type' => 1, 'subject' => $body_reset_password_subject],
            ['name' => '2fa-verification-code', 'format' => $fa_verifiction_code, 'body' => $body_fa_verification_code, 'tag' => '2fa_verification_code', 'type' => 1, 'subject' => $subject_fa_verification_code],
            ['name' => 'b2b-signup-link', 'format' => $b2b_signup_link, 'body' => $b2b_signup_link_body, 'tag' => 'b2b_signup_link', 'type' => 2, 'subject' => $b2b_signup_link_subject],
            ['name' => 'b2b-login-link', 'format' => $b2b_login_link, 'body' => $b2b_login_link_body, 'tag' => 'b2b_login_link', 'type' => 2, 'subject' => $b2b_login_link_subject],
            ['name' => 'b2b-maestro-signup', 'format' => $maestro_signup, 'body' => $b2b_maestro_signup_body, 'tag' => 'maestro_signup', 'type' => 2, 'subject' => $b2b_maestro_signup_subject],
            ['name' => 'b2c-signup', 'format' => $b2c_signup, 'body' => $b2c_signup_body, 'tag' => 'b2c_signup', 'type' => 1, 'subject' => $b2c_signup_subject],
            ['name' => 'Verify Your Email Address For Maestro HumanOp', 'format' => $b2b_email_verification, 'body' => $body_b2b_email_verified, 'tag' => 'b2b_email_verification', 'type' => 2, 'subject' => $body_b2b_email_verified_subject],
        ];

        DB::table('email_templates')->insert($email_template);
    }
}
