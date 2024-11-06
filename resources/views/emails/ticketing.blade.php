<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            color: #333333;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777777;
            border-top: 1px solid #eeeeee;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            font-size: 16px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Support Ticket Response</h1>
        </div>
        <div class="content">
            <p>Hi {{ $user->first_name }},</p>
            <p>Thank you for reaching out to us. We have received your ticket and our team is currently reviewing your issue. Here are the details:</p>
            <p><strong>Ticket ID:</strong> {{ $ticket->ticket_id }}</p>
            <p><strong>Description:</strong></p>
            <p>{{ $ticket->message }}</p>
            <p>We will get back to you with an update as soon as possible. If you have any additional information or questions, please reply to this email or contact our support team directly.</p>
            <p>Best regards,</p>
            <p>The {{ config('app.name') }} Support Team</p>
            {{-- <a href="{{ $supportUrl }}" class="btn">Visit Support Center</a> --}}
        </div>
        <div class="footer">
            <p>&copy;{{  Date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            {{-- <p>{{ config('app.address') }}</p> --}}
        </div>
    </div>
</body>
</html>
