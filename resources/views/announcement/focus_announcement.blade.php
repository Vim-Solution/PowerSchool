
<img class="card-img-top responsive-img" src="{{ asset($notifier->profile)}}" alt="" height="280">
<div class="card-body">
    <h4 class="card-title text-center"> {{ucwords(strtolower($notifier->full_name))}} </h4>
    <h6 class="bold"> {{ $announcement->notification_subject }}n</h6>
    <p class="card-text p-lg-3">{{ $announcement->notification_body }} </p>
    <a href="{{trans('settings/routes.announcement') }}" class="btn c-ewangclarks"
       style="width: 28%;position: relative;left: 70%;">
        <i class="zmdi zmdi-arrow-back"></i> @lang('announcement/announcement.announcement_title')
    </a>
</div>
