@extends('layouts.app')

@section('title')
    @lang('notifications/notification.notification_header')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-7">
            <div class="card">
             {!! $current_notification !!}
                <br>
                <br><br>
            </div>
        </div>

        <div class="col-lg-4 col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('notifications/notification.notification_caption')</h4>
                    <h6 class="card-subtitle">@lang('notifications/notification.notification_caption_info')</h6>
                </div>

                <div class="listview listview--hover">
                     {!! $active_notifications !!}
                    <div class="m-4"></div>
                </div>
                <br><br>
            </div>
        </div>
    </div>
@endsection