<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Guppa Admin Panel</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
<h2>Welcome to Guppa Admin Panel!</h2>

<p>Dear {{ $user->first_name }},</p>

<p>We are excited to welcome you as a  {{ $user->role }} for <strong>Guppa</strong>! 🎉</p>

<p>To get started, please complete your onboarding by setting up your account and creating your password. This will enable you to log in and start managing your responsibilities.</p>

<h3>Action Required:</h3>
<ol>
    <li>Click the link below to access the onboarding page.</li>
    <li>Follow the instructions to create your password.</li>
    <li>Once done, you will be able to log in to your account.</li>
</ol>

<p>
    <a href="{{ $url }}" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">
        Complete Your Onboarding
    </a>
</p>
<p>
    <a style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">
      unable to click the button? copy the link to a new browser tab:   {{ $url }}
    </a>
</p>

<p>If you have any questions or need assistance during this process, feel free to reach out to our support team at <a href="mailto:superuser@guppa.com">superuser@guppa.com</a> </p>

<p>We look forward to your contributions and wish you great success in your new role!</p>

<p>Best regards,</p>
<p>The Guppa Name Team</p>
</body>
</html>
