<!DOCTYPE html>
<html>
<head>
    <title>Job Application Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .content {
            margin: 20px;
        }
        .header {
            background-color: #f8f8f8;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .details {
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="header">
        <h2>Job Application Submitted Successfully</h2>
    </div>
    <p>Dear {{ $freelancer->last_name }},</p>
    <p>Your application for the job "<strong>{{ $job->title }}</strong>" has been submitted successfully. Please await further response from the client.</p>

    <div class="details">
        <h3>Job Details</h3>
        <p><strong>Title:</strong> {{ $job->title }}</p>
        <p><strong>Description:</strong> {{ $job->description }}</p>
    </div>

    <p>Thank you for your interest in this job. We will notify you once the client responds to your application.</p>

    <p>Best regards,</p>
    <p>The Guppa Platform Team</p>
</div>
</body>
</html>
