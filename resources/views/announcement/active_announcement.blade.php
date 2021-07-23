@foreach ($activeAnnouncements as $activeAnnouncement)
    @php $notifier = App\User::find($activeAnnouncement->notifier_id) @endphp
    <a href="{{ trans('settings/routes.announcement') . '/' . App\Encrypter::encrypt($activeAnnouncement->notification_id) }}"
       class="listview__item">
        <div class="c-ewangclarks"
             style="content:\'\';width:10px;height:10px;color:#FFF;border-radius:50%;position: relative;top: 15px;">

        </div>
        <img src="{{ asset($notifier->profile) }}" class="listview__img" alt="" style="padding-left: 5px;">
        <div class="listview__content">
            <div class="listview__heading text-truncate c-r">
                {{ ucwords(strtolower($notifier->full_name))}}
            </div>
            <p>{{ $activeAnnouncement->notification_subject }}</p>
        </div>
    </a>
@endforeach
