
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
</head>
<body>
<div style="background-color: #f0f0f0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h2 style="font-size: 24px; color: #333333; margin-bottom: 20px;">Hello, {{ $user->last_name }}!</h2>

        <p style="font-size: 16px; color: #666666;"> Welcome to Our Platform</p>

        <p style="font-size: 16px; color: #666666;">Thank you for registering. We're excited to have you on board </p>

        <p style="font-size: 16px; color: #666666;">
            Get Started by verifying your email address using the code below
         <h3>{{ $code }}</h3>
        <a href="">Verify Email</a>
        </p>

        <p style="font-size: 16px; color: #666666;">We are glad to have you here</p>

        <p style="font-size: 16px; color: #666666;">Thank you!</p>
    </div>
</div>
</body>
</html>
