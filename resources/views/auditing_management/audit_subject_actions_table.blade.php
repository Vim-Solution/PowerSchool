<h4 class="text-center">
    <b>{{ trans('auditing_management/audit_subject_actions.audit_action_list_title',['year' => $academic_year]) }}</b>
</h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('auditing_management/audit_subject_actions.sn')</th>
            <th>@lang('auditing_management/audit_subject_actions.action')</th>
            <th>@lang('auditing_management/audit_subject_actions.action_by')</th>
            <th>@lang('auditing_management/audit_subject_actions.action_on')</th>
            @if($action == 5)
                <th>@lang('auditing_management/audit_subject_actions.series_from')</th>
                <th>@lang('auditing_management/audit_subject_actions.series_to')</th>
            @endif
            <th>@lang('auditing_management/audit_subject_actions.sequence_upon')</th>
            <th>@lang('auditing_management/audit_subject_actions.date')</th>
        </tr>
        </thead>
        <tbody>
        @if($action == 0)
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $sn++ }}</td>
                    <td>{{ App\Setting::getActionManyInterpretationByState($action,trans('general.subject')) }}</td>
                    <td>{{App\User::getUserNameById($activity->users_user_id)}}</td>
                    <td>{{ App\User::getUserNameById($activity->subject_id) }}</td>
                    <td></td>
                    <td>{{ date('F d,Y',strtotime($activity->created_at)) }}</td>
                </tr>
            @endforeach
        @elseif($action == 5)
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $sn++ }}</td>
                    <td>{{ App\Setting::getActionManyInterpretationByState($action,trans('general.subject')) }}</td>
                    <td>{{App\User::getUserNameById($activity->users_user_id)}}</td>
                    <td>{{ App\Subject::getSubjectTitleById($activity->subjects_subject_id) }}</td>
                    <td> {{ $activity->series_code }}</td>
                    <td> {{ $activity->series_series_code }}</td>
                    <td> {{ App\Sequence::getSequenceNameById($activity->sequences_sequence_id) }}</td>
                    <td>{{ date('F d,Y',strtotime($activity->created_at)) }}</td>
                </tr>
            @endforeach
        @else
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $sn++ }}</td>
                    <td>{{ App\Setting::getActionManyInterpretationByState($activity->state,trans('general.subject')) }}</td>
                    <td>{{App\User::getUserNameById($activity->users_user_id)}}</td>
                    <td>{{ $activity->subjects_subject_name }}</td>
                    <td>{{$activity->sequences_sequence_name}}</td>
                    <td>{{ date('F d,Y',strtotime($activity->created_at)) }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
