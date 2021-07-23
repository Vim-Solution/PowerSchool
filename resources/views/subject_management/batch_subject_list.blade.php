<h4 class="text-center">
    <b>{{ trans('subject_management/batch_subject_upload.subject_list_title',['class' => $class->class_name,'year' => \App\Setting::getAcademicYear()])  }}</b>
</h4>
<br>
<div class="table-responsive">
    <table id="data-table" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
                <th>@lang('subject_management/batch_subject_upload.subject_code')</th>
                <th>@lang('subject_management/batch_subject_upload.subject_title')</th>
                <th>@lang('subject_management/batch_subject_upload.coefficient')</th>
                <th>@lang('subject_management/batch_subject_upload.state')</th>
                <th>@lang('subject_management/batch_subject_upload.series')</th>
                <th>@lang('subject_management/batch_subject_upload.class')</th>
                <th>@lang('subject_management/batch_subject_upload.subject_weight')</th>
                <th>@lang('subject_management/batch_subject_upload.cycle_name')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($subjects as $subject)
            <tr>
                <td style = "color: red">{{$subject->subject_code }}</td>
                <td>{{$subject->subject_title }}</td>
                <td>{{$subject->coefficient }}</td>
                <td>{{$subject->state }}</td>
                <td>{{$subject->series_series_code }}</td>
                <td>{{$subject->classes_class_code}}</td>
                <td>{{$subject->subject_weight }}</td>
                <td>{{\App\Program::getCycleNameByCode($subject->programs_program_code)}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div><br>