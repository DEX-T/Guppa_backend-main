<!DOCTYPE html>
<html>
<head>
    <title>New Job Application</title>
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
        .milestones {
            margin: 20px 0;
        }
        .milestone {
            padding: 5px 0;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="header">
        <h2>New Job Application Received</h2>
    </div>
    <p>Dear {{ $client->name }},</p>
    <p>A new application has been submitted for the job "<strong>{{ $job->title }}</strong>" by <strong>{{ $freelancer->last_name }} {{ $freelancer->first_name }}</strong>.</p>

    <div class="details">
        <h3>Freelancer Details</h3>
        <p><strong>Name:</strong> {{ $freelancer->last_name }} {{ $freelancer->first_name }}</p>
        <p><strong>Email:</strong> {{ $freelancer->email }}</p>
        <p><strong>Profile:</strong> <a href="{{ url('/freelancer/' . $freelancer->id) }}">View Profile</a></p>
    </div>

    <div class="details">
        <h3>Job Details</h3>
        <p><strong>Title:</strong> {{ $job->title }}</p>
        <p><strong>Description:</strong> {{ $job->description }}</p>
        <p><strong>Project Timeline:</strong> {{ $appliedJob->project_timeline }}</p>

    </div>

    @if($appliedJob->milestones->isNotEmpty())
        <div class="milestones">
            <h3>Job Milestones</h3>
            @foreach($appliedJob->milestones as $milestone)
                <div class="milestone">
                    <p><strong>{{ $loop->iteration }}. {{ $milestone->milestone_description }}</strong></p>
                    <p>{{ $milestone->milestone_amount }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <p>Please review the application and get in touch with the freelancer if you wish to proceed.</p>
    <p><strong>Job Dashboard:</strong> <a href="{{ url('/freelancer/' . $freelancer->id) }}">View Job</a></p>

    <p>Best regards,</p>
    <p>The Job Platform Team</p>
</div>
</body>
</html>
