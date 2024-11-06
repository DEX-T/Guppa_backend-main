<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f1f1f1;
            color: #555555;
            padding: 10px;
            text-align: center;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Verification Status</h1>
    </div>
    <div class="content">
        <p>Dear {{$client->first_name}},</p>
        <p>We are writing to inform you that your verification has been <strong>{{$verification->status}}</strong>.</p>

        @if($verification->status == "approved")
        <p>Congratulations! Your account has been successfully verified. You can now access all the features and services available to our verified clients.</p>
        <a href="/" class="button">Explore Now</a>
        @else
        <p>Unfortunately, we were unable to verify your account at this time. If you have any questions or believe this to be a mistake, please contact our support team for further assistance.</p>
        <a href="mailto:support.kyc@guppa.com" class="button">Contact Support</a>
        @endif

        <p>Thank you for your understanding.</p>
        <p>Best regards,<br>The Guppa Team</p>
    </div>
    <div class="footer">
        <p>&copy; {{ Date('Y')  }} Guppa. All rights reserved.</p>
        <p><a href="/">Privacy Policy</a> | <a href="/">Terms of Service</a></p>
    </div>
</div>
</body>
</html>
