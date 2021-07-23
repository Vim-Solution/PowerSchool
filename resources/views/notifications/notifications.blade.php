@extends('layouts.app')

@section('title')
    @lang('notifications/notification.notification_header')
@endsection

@section('content')
    @if(session('status'))
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show text-center">
                {!!  session('status') !!}
            </div>
        </div>
    @endif
    <div class="card issue-tracker">
        <div class="toolbar toolbar--inner">
            <div class="toolbar__nav">
                <a class="active" href="#"><span class="badge badge-pill c-ewangclarks">{{ trans('notifications/notification.read') . ' ' . $read }}</span></a>
                <a href="#" class="hidden-sm-down"><span class="badge badge-pill badge-danger">{{ trans('notifications/notification.unread') . ' ' . $unread }}</span></a>
            </div>
        </div>

        <div class="listview listview--bordered">
            {!! $notifications !!}
        </div>
    </div>
@endsection