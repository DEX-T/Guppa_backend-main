<!DOCTYPE html>
<html>
<head>
    <title>Job Invite Status</title>
</head>
<body>
    <h1>Hello {{ $client->first_name }},</h1>

    @if($invites->status === 'accepted')
        <p>We are pleased to inform you that {{ $freelancer->first_name }} has accepted your invitation to bid for the job:</p>
    @else
        <p>We regret to inform you that {{ $freelancer->first_name }} has declined your invitation to bid for the job:</p>
    @endif

    <h2>{{ $job->title }}</h2>
    <p>{{ $job->description }}</p>

    <p>Thank you,</p>
    <p>Guppa</p>
</body>
</html>
