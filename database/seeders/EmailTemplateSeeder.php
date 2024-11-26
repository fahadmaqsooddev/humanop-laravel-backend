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

        $reset_password = '
        <h3>Reset Password</h3>
    <p>Hi, {$userName}</p>
    <p>We received a request to reset your password for your HumanOp account. If you did not request this change, you can safely ignore this email.</p>
    <p>Please click the link below to reset your password:</p>
    <a href="{$link}" target="_blank" style="color: #f2661c; font-weight: bold; font-size: 20px"> Reset Password</a>
    <p>If you did not create an account, please email support@humanop.com asap to make sure we address the error.</p>';

        $invite_link = '
        <h3>You are Invited to Join HumanOp!</h3>
<p>Hello,</p>
<p>We’re excited to invite you to create your account on HumanOp. Click the link below to complete your registration and start exploring the features we offer:</p>
<a href="{$link}" target="_blank" style="color: #f2661c; font-weight: bold; font-size: 20px">Complete Your Registration</a>
<p>If you did not request this invitation, please ignore this email or contact our support team at <a href="mailto:support@humanop.com" style="color: #f2661c">support@humanop.com</a> to report any issues.</p>
<p>We look forward to having you on board!</p>
<p>Best regards,</p>
<p>The HumanOp Team</p>';

        $email_template = [
            ['name' => 'email-verification', 'format' => $email_verification],
            ['name' => 'reset-password', 'format' => $reset_password],
            ['name' => 'invite-link', 'format' => $invite_link],
        ];

        DB::table('email_templates')->insert($email_template);
    }
}
