<h4 class="text-center">
    <b>{{ trans('result_management/manage_public_exams.manage_public_exams_list_title') }}</b></h4><br>

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('result_management/manage_public_exams.center_no_title')</th>
            <th>@lang('result_management/manage_public_exams.cycle_name')</th>
            <th>@lang('result_management/manage_public_exams.exam_type')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($examSettings as $examSetting)
            <tr>
                <td>{{$examSetting->center_no}}</td>
                <td>{{\App\Program::getCycleNameByCode($examSetting->programs_program_code)}}</td>
                @if($examSetting->programs_program_code == trans('result_management/manage_public_exams.gce_ol_code'))
                    <td>{{ trans('result_management/manage_public_exams.gce_ol')  }}</td>
                @else
                    <td>{{ trans('result_management/manage_public_exams.gce_al')  }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
