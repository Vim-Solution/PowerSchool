@if($error_counter > 0)
    <b style="color: red;font-size: 12px;">{{ trans('student_management/batch_student_upload.download_student_list') }}</b></h4><br>
    <a href="{{ trans('settings/routes.download_student_list') }}"
       class="btn c-ewangclarks"
       style="width: 30%;position: relative;left: 70%;">
        <h6 class="text-white"><i
                    class="zmdi zmdi-cloud-download"></i>@lang('student_management/batch_student_upload.download_log')
        </h6>
    </a>
@endif
<br><br><br>
<h4 class="text-center">
    <b>{{ trans('student_management/batch_student_upload.student_list_title',['class' => $class->class_name,'year' => \App\Setting::getAcademicYear()])  }}</b>
</h4>
<br>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('student_management/batch_student_upload.matricule')</th>
            <th>@lang('student_management/batch_student_upload.full_name')</th>
            <th>@lang('student_management/batch_student_upload.date_of_birth')</th>
            <th>@lang('student_management/batch_student_upload.place_of_birth')</th>
            <th>@lang('student_management/batch_student_upload.region_of_origin')</th>
            <th>@lang('student_management/batch_student_upload.father_address')</th>
            <th>@lang('student_management/batch_student_upload.mother_address')</th>
            <th>@lang('student_management/batch_student_upload.tutor_name')</th>
            <th>@lang('student_management/batch_student_upload.tutor_address')</th>
            <th>@lang('student_management/batch_student_upload.admission_date')</th>
            <th>@lang('student_management/batch_student_upload.cycle_name')</th>
            <th>@lang('student_management/batch_student_upload.section_name')</th>

        </tr>
        </thead>
        <tbody>
        @foreach($students as $student)
            <tr>
                <td style="color: red">{{$student['matricule'] }}</td>
                <td>{{$student['full_name'] }}</td>
                <td>{{date('d F Y',strtotime($student['date_of_birth'])) }}</td>
                <td>{{$student['place_of_birth'] }}</td>
                <td>{{$student['region_of_origin'] }}</td>
                <td>{{$student['father_address'] }}</td>
                <td>{{$student['mother_address'] }}</td>
                <td>{{$student['tutor_name'] }}</td>
                <td>{{$student['tutor_address'] }}</td>
                <td>{{ date('d F Y',strtotime($student['admission_date'])) }}</td>
                <td>{{\App\Program::getCycleNameByCode($student['programs_program_code'])}}</td>
                <td>{{ \App\SchoolSection::getSectionNameByCode($student['sections_section_code']) }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div><br>
