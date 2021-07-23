<h4 class="text-center">
    <b>{{ trans('auditing_management/audit_setting_actions.audit_action_list_title',['year' => $academic_year]) }}</b></h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('auditing_management/audit_setting_actions.sn')</th>
            <th>@lang('auditing_management/audit_setting_actions.action')</th>
            <th>@lang('auditing_management/audit_setting_actions.action_by')</th>
            <th>@lang('auditing_management/audit_setting_actions.action_on')</th>
            <th>@lang('auditing_management/audit_setting_actions.sequence_upon')</th>
            <th>@lang('auditing_management/audit_setting_actions.date')</th>
        </tr>
        </thead>
        <tbody>

            @foreach($activities as $activity)
                <tr>
                    <td>{{ $sn++ }}</td>
                    <td>{{ trans('auditing_management/audit_setting_actions.academic_setting_change',['year' => $activity->a_year ]) }}</td>
                    <td>{{App\User::getUserNameById($activity->users_user_id)}}</td>
                    <td>{{trans('auditing_management/audit_setting_actions.academic_setting',['year' => $activity->sequence_name . ' ' . $activity->a_year ]) }}</td>
                    <td>{{$activity->sequences_sequence_name}}</td>
                    <td>{{ date('F d,Y',strtotime($activity->created_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
