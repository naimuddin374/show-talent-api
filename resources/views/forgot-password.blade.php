<!DOCTYPE html>
    <html lang="en" style="margin: 0;padding: 0;font-family: Montserrat;">
    <head style="margin: 0;padding: 0;">
        <meta charset="UTF-8" style="margin: 0;padding: 0;">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" style="margin: 0;padding: 0;">
        <meta http-equiv="X-UA-Compatible" content="ie=edge" style="margin: 0;padding: 0;">
        <!-- google font-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,900&display=swap" rel="stylesheet" style="margin: 0;padding: 0;">
        <title style="margin: 0;padding: 0;">Beatnik</title>
    </head>
    <body style="margin: 0;padding: 0;font-family:Montserrat;">
        <div class="right-tab-content" style="margin: 0 auto;padding: 11px 34px;border: 1px solid #35ABA7;-webkit-border-radius: 10px;border-radius: 10px;width: 25%;position: relative;">
            <div class="shape-item" style="margin: 0;padding: 0;"></div>
                <h4 style="margin: 0;padding: 0;">Dear, {{$user_name}}</h4>
                <h4 style="margin: 0;padding: 0;">Here below is the password reset link, please go to the link to reset the password.</h4>
                <h5 style="margin: 0;padding: 0;color:blue;"><a href="http://localhost:3006/reset-password/{{$user_key}}">Click here</a></h5><br/>
                <h5 style="margin: 0;padding: 0;">If you have any queries feel free to contact with us.</h5><br/>
            </div>
        </div>
    </body>
</html>
