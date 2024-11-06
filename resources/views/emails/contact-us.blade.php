<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $subject }}}</title>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap");

      body {
        font-family: "Montserrat", sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
      }
      .container {
        max-width: 600px;
        margin: 40px auto;
        background-color: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      }
      .header {
        background: linear-gradient(135deg, #1c015a, #4f02cc);
        text-align: center;
        padding: 40px 20px;
      }
      .header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 600;
        color: #ffffff;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
      }
      .content {
        padding: 40px 30px;
        text-align: justify;
      }
      .icon {
        font-size: 72px;
        margin-bottom: 20px;
      }
      .button {
        display: inline-block;
        background: linear-gradient(135deg, #1a0155, #6712f0);
        color: #ffffff;
        text-decoration: none;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 600;
        margin-top: 25px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }
      .button:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      }
      .footer {
        background-color: #f9f9f9;
        text-align: center;
        padding: 20px;
        font-size: 14px;
        color: #888;
      }
      @media only screen and (max-width: 600px) {
        .container {
          margin: 20px;
          border-radius: 15px;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>{{ $subject }}</h1>
      </div>
      <div class="content">
        <div class="text-justify">
            <p>
                Good day, {{ $name }}
            </p>
            <p>
               {{ $content }}
            </p>
              
        </div>
      </div>
      <div class="footer">&copy; {{ date('Y') }} Global Services Guppa. Where dreams become projects.</div>
    </div>
  </body>
</html>

