<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: auto; padding: 20px; }
        .header { text-align: center; }
        .button { background-color: #007BFF; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; }
        .footer { margin-top: 20px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="your-logo-url" alt="Your Company Logo" width="150">
        </div>
        <p>Hi {{ $lastName }},</p>
        <p>We received a request to reset your password for your Guppa account. If you did not make this request, you can ignore this email.</p>
        <p>Otherwise, you can reset your password using the button below:</p>
        <p style="text-align: center;">
            <a href="{{ $url }}" class="button">Reset Password</a>
        </p>
        <p>Alternatively, you can copy and paste the following link into your browser:</p>
        <p><a href="{{ $url }}">{{ $url }}</a></p>
        <p>This link will expire in 5 mins for your security.</p>
        <p>If you continue to have trouble accessing your account, please contact our support team at <a href="mailto:support@guppa.com">support@guppa.com</a>.</p>
        <p>Thank you,<br>The Guppa Team</p>
        <div class="footer">
            <p>If you did not request a password reset, please ensure your account security by updating your password and reviewing your account activity. For additional security tips, visit our <a href="[Security Tips Page]">Security Tips Page</a>.</p>
        </div>
    </div>
</body>
</html>
