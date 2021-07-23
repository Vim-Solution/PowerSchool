
@foreach($announcements as $announcement)
    @php
        $notifier = App\User::find($announcement->notifier_id);
        strtok($announcement->created_at, " ");
         $readState = $announcement->state == 1 ? '<span class="issue-tracker__tag bg-blue" >' . trans('announcement/announcement.read') . '</span >' : '<span class="issue-tracker__tag bg-red" >' . trans('announcement/announcement.unread') . '</span >';
    @endphp
    <div class="listview__item">
        <i class="avatar-char bg-amber">{{ $notifier->full_name[0] }} </i>
        <div class="listview__content text-truncate text-truncate">
            <a class="listview__heading"
               href="{{trans('settings/routes.announcement') . '/' . App\Encrypter::encrypt($announcement->notification_id) }}"> {{ ucwords(strtolower($notifier->full_name))}}</a>
            <p> {{ ucfirst(strtolower($announcement->notification_subject)) }}</p>
        </div>
        <div class="issue-tracker__item hidden-sm-down"> {!! $readState !!}
        </div>
        <div class="issue-tracker__item hidden-md-down">
            <i class="zmdi zmdi-time"></i> {{ date('d/m/Y', strtotime($announcement->created_at)) . ' at ' . strtok(" ") }}
        </div>
    </div>
@endforeach
