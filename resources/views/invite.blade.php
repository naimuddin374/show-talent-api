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
                <h4 style="margin: 0;padding: 0;">{{$send_user}} invite you to attend the meaning, please check the below meeting info:</h4>
                <h4 style="margin: 0;padding: 0;margin-bottom: 20px;font-size: 21px;font-weight: 600;text-align: center;">Meeting Information</h4>
                <hr class="margin-bottom" style="margin: 0;padding: 0;margin-bottom: 10px;">
                <h5 style="margin: 0;padding: 0;">Agenda: {{$note}}</h5>
                <h5 style="margin: 0;padding: 0;">Date: {{$date}}</h5>
                <h5 style="margin: 0;padding: 0;">Start Time: {{$start_time}}</h5>
                <h5 style="margin: 0;padding: 0;">End Time: {{$end_time}}</h5><br/>
                <h5 style="margin: 0;padding: 0;color:blue;"><a href="http://localhost:3000/meeting-detail/{{$meetingId}}">Click here</a></h5><br/>
                <h5 style="margin: 0;padding: 0;">If you have any question feel free to contact with creator.</h5><br/>
            </div>
        </div>
    </body>
</html>
