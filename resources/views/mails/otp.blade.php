<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <h2>Dear {{ $name }},</h2>
    {{-- <p>Your OTP code is: {{ $otp }}</p> --}}
    <p>To enhance the security of your ELS Dashboard account, we have implemented a One-Time Password (Code) authentication method.

        <br>
        <br>
        Please use the following Code to complete your login:

        <br>
        <br>
        <p style="font-weight: bold;">
        {{ $otp }}
        </p>
        <p>Click the button below to reset your password:</p>
        <a href="{{ $resetLink }}" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none;">Reset Password</a>
        {{-- <a href="{{ url('/reset-password') }}?email={{ $email }}&otp={{ $otp }}" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none;">Reset Password</a> --}}
        <br>
        <br>
        Please ensure that you enter this Code within the specified time frame. It is valid for a single use only. Keep this code confidential and do not share it with anyone, including the Mujib Dashboard support team.

        <br>
        <br>
        If you did not request this Code or have any concerns about your account's security, please contact our support team immediately.

        <br>
        <br>
        Best regards,
        <br>
        <br>
        Support Team</p>
        <br>
        <br>
    {{-- <a href="{{ url('/verified_login/') }}">submit Your Code</a> --}}




</body>
</html>
