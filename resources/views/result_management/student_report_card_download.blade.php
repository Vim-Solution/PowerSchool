<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{ public_path('template/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ public_path('template/css/app.min.css') }}">
    <link href="{{ public_path('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="card">
        <div class="card-body">
            @foreach($report_card as $report_c)
                {!! $report_c !!} <br>
            @endforeach
        </div>
    </div>
    <style type="text/css">
        .profile {
            margin-top: 75px;
            text-align: center
        }

        .c-ewangclarks {
            background-color: @lang('settings/theme.c-ewangclarks');
            color: white;
        }

        .c-ewangclarks:hover {
            background-color: @lang('settings/theme.c-ewangclarks');
            color: white;
        }

        .c-ewangclarks:active {
            background-color: @lang('settings/theme.c-ewangclarks');
            color: white;
        }

        .c-ewangclarks:focus {
            background-color: @lang('settings/theme.c-ewangclarks');
            color: white;
        }

        .profile__img {
            padding: 5px;
            position: relative
        }

        .profile__img img {
            max-width: 200px;
            border-radius: 2px
        }

        .profile__info {
            padding: 30px
        }
    </style>
</body>
</html>


