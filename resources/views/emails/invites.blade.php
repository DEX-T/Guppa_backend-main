<!DOCTYPE html>
<html>
<head>
    <title>Job Invitation</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: auto; padding: 20px; }
        .header { text-align: center; }
        .button { background-color: #007BFF; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; }
        .footer { margin-top: 20px; font-size: 12px; color: #888; text-align: center; }
        .details {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="your-logo-url" alt="Your Company Logo" width="150">
        </div>
        <p>Hi {{ $freelancer->first_name }},</p>
        <p>I'm inviting you to bid for project, according to our system this job matches your skills  and experience.</p>
        <p>Find Job details below:</p>
        <div class="details">
            <h3>Job Details</h3>
            <p><strong>Title:</strong> {{ $job->title }}</p>
            <p class="text-justify">
                <strong>Description</strong> 
                {{ $job->description }}
            </p>
            <p><strong>Amount:</strong> {{ $job->amount }}</p>
            <p><strong>Bid points required:</strong> {{ $job->bid_points }}</p>
        </div>
        <p>Please go to your dashboard, invites and accept the invite we can talk more:</p>
        <p><a href="{{ $url }}">Dashboard-Invites</a></p>
        <p>This link will take you to your dashboard.</p>
        <p>If you have trouble accessing your account, please contact our support team at <a href="mailto:support@guppa.com">support@guppa.com</a>.</p>
        <p>Thank you,<br>The Guppa Team</p>
        <div class="footer">
            <p>If you did not trust this mail, please ensure your account security by contacting the guppa support team for clearification</p>
        </div>
    </div>
</body>
</html>
