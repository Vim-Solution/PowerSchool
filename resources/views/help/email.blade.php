<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', trans('settings/setting.app_name')) }}</title>

    <!-- Vendor styles -->
    <link rel="stylesheet"
          href="{{ asset('template/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <script src="{{ asset('template/vendors/jquery/jquery.min.js') }}"></script>

    <!-- App styles -->
    <link rel="stylesheet" href="{{ asset('template/css/app.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="card">
    <div class="card-header card-danger">
        <h2>@lang('settings/setting.school_acronym')!</h2>
        <h3>@lang('settings/setting.school_name')!</h3>
    </div>
    <div class="card-body">
        <h3>@lang('general.app_name')</h3>
        <h3>@lang('help/help.user_type') {{$mailable['sender'] }}<br>@lang('help/help.tel') {{ $mailable['contact'] }} </h3>
        <h3>@lang('help/help.title')</h3>
        <h4>{{ $mailable['title']}}</h4>
        <h3>@lang('help/help.content')</h3>
        <h4>{{ $mailable['message'] }}</h4>;
    </div>
</div>
</body>

</html>
