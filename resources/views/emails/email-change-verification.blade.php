<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Lato, Arial, Helvetica, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #083321;">
        <h1 style="color: #083321; margin: 0;">Lomo Tanzania Safari</h1>
    </div>
    <div style="padding: 30px 0;">
        <h2 style="color: #333;">Verify Your New Email Address</h2>
        <p>Hi {{ $userName }},</p>
        <p>You requested to change your email address. Please click the button below to verify your new email:</p>
        <div style="text-align: center; padding: 20px 0;">
            <a href="{{ $verificationUrl }}" style="background-color: #083321; color: #FEBC11; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Verify Email Address</a>
        </div>
        <p style="color: #666; font-size: 14px;">If you did not request this change, please ignore this email. Your current email will remain unchanged.</p>
        <p style="color: #666; font-size: 14px;">This link expires in 60 minutes.</p>
    </div>
    <div style="border-top: 1px solid #eee; padding-top: 15px; color: #999; font-size: 12px; text-align: center;">
        &copy; {{ date('Y') }} Lomo Tanzania Safari. All rights reserved.
    </div>
</body>
</html>
