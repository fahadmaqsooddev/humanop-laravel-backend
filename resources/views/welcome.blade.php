<!DOCTYPE html>
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
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Welcome to HumanOp! Please verify your email address by entering the following verification code:</p>
        <div style="display: flex; justify-content: center;">
            <p style="color: #003a6d;font-size: 36px;background-color: darkgray;padding: 10px;border-radius: 10px;font-weight: bold; margin-right: 5px">{$code}</p>
        </div>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> Please enter this code on the verification page to log in to your account. Once verified, you will gain full access to all our features and services.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;"> If you didn’t create a HumanOp account, you can safely ignore this email.</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Thank you for joining HumanOp. We’re excited to have you on board!</p>
        <p style="margin: 12px 0; line-height: 1.5; color: black;">Best Regards,<br>HumanOP Team</p>
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
</html>
