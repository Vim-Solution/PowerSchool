<h4 class="text-center">
    <b>{{ trans('auditing_management/audit_term_actions.audit_action_list_title',['year' => $academic_year]) }}</b></h4>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('auditing_management/audit_term_actions.sn')</th>
            <th>@lang('auditing_management/audit_term_actions.action')</th>
            <th>@lang('auditing_management/audit_term_actions.action_by')</th>
            <th>@lang('auditing_management/audit_term_actions.action_on')</th>
            <th>@lang('auditing_management/audit_term_actions.sequence_upon')</th>
            <th>@lang('auditing_management/audit_term_actions.date')</th>
        </tr>
        </thead>
        <tbody>
        @if($action == 0)
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $sn++ }}</td>
                    <td>{{ App\Setting::getActionThreeInterpretationByState($action,trans('general.term')) }}</td>
                    <td>{{App\User::getUserNameById($activity->users_user_id)}}</td>
                    <td>{{ App\Term::getTermNameById($activity->term_id) }}</td>
                    <td></td>
                    <td>{{ date('F d,Y',strtotime($activity->created_at)) }}</td>
                </tr>
            @endforeach
        @else
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $sn++ }}</td>
                    <td>{{ App\Setting::getActionThreeInterpretationByState($activity->state,trans('general.term')) }}</td>
                    <td>{{App\User::getUserNameById($activity->users_user_id)}}</td>
                    <td>{{ $activity->terms_term_name }}</td>
                    <td>{{$activity->sequences_sequence_name}}</td>
                    <td>{{ date('F d,Y',strtotime($activity->created_at)) }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
