
<img class="card-img-top responsive-img" src="{{ asset($notifier->profile)}}" alt="" height="280">
<div class="card-body">
    <h4 class="card-title text-center"> {{ucwords(strtolower($notifier->full_name))}} </h4>
    <h6 class="bold"> {{ $notification->notification_subject }}n</h6>
    <p class="card-text p-lg-3">{{ $notification->notification_body }} </p>
    <a href="{{trans('settings/routes.notifications') }}" class="btn c-ewangclarks"
       style="width: 28%;position: relative;left: 70%;">
        <i class="zmdi zmdi-arrow-back"></i> @lang('notifications/notification.notification_header')
    </a>
</div>
