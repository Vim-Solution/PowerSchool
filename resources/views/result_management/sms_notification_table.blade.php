@if(!empty($sms_notification_records))
    <h5 class="text-center">
        <b>{{ trans('result_management/sms_notification.student_list_title',['year' => $academicYear,'class' => $className])  }}</b>
    </h5>
    <br>
    <a href="{{trans('settings/routes.g_sms_notifications') }}"
       class="btn c-ewangclarks text-white" style="width: 38%;position: relative;left: 63%;">
        <i class="zmdi zmdi-notifications"></i></i>{{trans('result_management/sms_notification.general_notification')}}

    </a>
    <div class="table-responsive">
        <table id="@lang('general.data_table')" class="table table-striped">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('result_management/sms_notification.matricule')</th>
                <th>@lang('result_management/sms_notification.full_name')</th>
                <th>@lang('result_management/sms_notification.class')</th>
                <th>@lang('result_management/sms_notification.tel')</th>
                <th>@lang('result_management/sms_notification.action')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sms_notification_records as $sms_notification_record)
                <tr>
                    <td>{{$sms_notification_record->matricule }}</td>
                    <td>{{$sms_notification_record->full_name }}</td>
                    <td>{{ $className}}</td>
                    <td>{{$sms_notification_record->father_address }}</td>
                    <td >
                        <a href="{{trans('settings/routes.s_sms_notifications') . '/' . \App\Encrypter::encrypt($sms_notification_record->matricule) }}"
                           class="btn bg-green"><h6 class="text-white" style="font-size: 12px;"><i
                                    class="zmdi zmdi-notifications-add"></i>{{trans('result_management/sms_notification.notify')}}
                            </h6></a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div><br>
    <a href="{{trans('settings/routes.c_sms_notifications') . '/' . \App\Encrypter::encrypt($classCode) }}"
       class="btn c-ewangclarks"
       style="width: 42%;position: relative;left: 30%;"><h6 class="text-white"><i
                class="zmdi zmdi-notifications-add"></i>{{trans('result_management/sms_notification.bulk_notification')}}
        </h6></a><br><br>
@else
    {!! \App\Setting::getAlertFailure(trans('result_management/sms_notification.no_student_alert')) !!}
@endif
