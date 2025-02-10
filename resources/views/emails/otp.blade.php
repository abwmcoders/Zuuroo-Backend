<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
</head>
<body style="margin:0; padding:0; background-color:#f2f2f2;">
    <!-- Outer Table -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Container with Gradient Background -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600"
                       style="background: linear-gradient(to right, #F772D1, #643D90); padding: 40px; border-radius: 8px;">
                    <!-- Company Logo -->
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <img src="{{ asset('images/logo.jpeg') }}" alt="Company Logo" style="max-width: 150px; display: block;">
                        </td>
                    </tr>
                    <!-- Welcome Text -->
                    <tr>
                        <td align="center" style="padding: 10px 0; font-family: Arial, sans-serif; color: #ffffff; font-size: 24px; font-weight: bold;">
                            Welcome to Zuuro!
We believe that no distance is too great when it comes to connecting with the ones you care about. Enjoy uninterrupted communication, wherever life takes you.
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 0; font-family: Arial, sans-serif; color: #ffffff; font-size: 16px;">
                            Please use the following 6-digit code to verify your account:
                        </td>
                    </tr>
                    <!-- OTP Code Display -->
                    <tr>
                        <td align="center" style="padding: 20px 0;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                   style="background-color: #ffffff; padding: 10px 20px; border-radius: 4px;">
                                <tr>
                                    <td align="center" style="font-family: Arial, sans-serif; font-size: 32px; font-weight: bold; color: #643D90;">
                                        {{ $otp }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Expiration Info -->
                    <tr>
                        <td align="center" style="padding: 10px 0; font-family: Arial, sans-serif; color: #ffffff; font-size: 14px;">
                            This OTP will expire in 15 minutes.
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 10px 0; font-family: Arial, sans-serif; color: #ffffff; font-size: 14px;">
                            If you did not request this OTP, please ignore this email.
                        </td>
                    </tr>
                    <!-- Copyright Footer -->
                    <tr>
                        <td align="center" style="padding-top: 30px; font-family: Arial, sans-serif; color: #ffffff; font-size: 12px;">
                            &copy; Copyright © 2022 Zuuro., Ltd. All Rights Reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
