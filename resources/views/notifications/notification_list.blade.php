@foreach ($notifications as $notification)
    @php
        $notifier = App\User::find($notification->notifier_id);
        strtok($notification->created_at, " ");
         $readState = $notification->state == 1 ? '<span class="issue-tracker__tag c-ewangclarks" >' . trans('notifications/notification.read') . '</span >' : '<span class="issue-tracker__tag bg-red" >' . trans('notifications/notification.unread') . '</span >';
    @endphp
    <div class="listview__item">
        <i class="avatar-char bg-amber">{{ $notifier->full_name[0] }} </i>
        <div class="listview__content text-truncate text-truncate">
            <a class="listview__heading"
               href="{{trans('settings/routes.notifications') . '/' . App\Encrypter::encrypt($notification->notification_id) }}"> {{ ucwords(strtolower($notifier->full_name))}}</a>
            <p> {{ ucfirst(strtolower($notification->notification_subject)) }}</p>
        </div>
        <div class="issue-tracker__item hidden-sm-down"> {!! $readState !!}
        </div>
        <div class="issue-tracker__item hidden-md-down">
            <i class="zmdi zmdi-time"></i> {{ date('d/m/Y', strtotime($notification->created_at)) . ' at ' . strtok(" ") }}
        </div>
        <div class="issue-tracker__item actions">
            <div class="dropdown actions__item">
                <i class="zmdi zmdi-more-vert" data-toggle="dropdown"></i>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu--active dropdown-menu--icon">
                    <a href="{{trans('settings/routes.delete_notification') . '/' . App\Encrypter::encrypt($notification->notification_id) }}"
                       class="dropdown-item"><i class="zmdi zmdi-delete"></i> {{trans('actions/action.delete')}}</a>
                </div>
            </div>
        </div>
    </div>
@endforeach
