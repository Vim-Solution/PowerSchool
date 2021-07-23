
    <b style="color: red;font-size: 12px;">{{ trans('student_management/add_student.added_student_title',['name' => $student->full_name,'class' => $class->class_name,'year' => \App\Setting::getAcademicYear()])  }}</b>
<br>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('student_management/add_student.matricule')</th>
            <th>@lang('student_management/add_student.full_name')</th>
            <th>@lang('student_management/add_student.date_of_birth')</th>
            <th>@lang('student_management/add_student.place_of_birth')</th>
            <th>@lang('student_management/add_student.region_of_origin')</th>
            <th>@lang('student_management/add_student.father_address')</th>
            <th>@lang('student_management/batch_student_upload.mother_address')</th>
            <th>@lang('student_management/batch_student_upload.tutor_name')</th>
            <th>@lang('student_management/batch_student_upload.tutor_address')</th>
            <th>@lang('student_management/add_student.admission_date')</th>
            <th>@lang('student_management/add_student.cycle_name')</th>
            <th>@lang('student_management/add_student.section_name')</th>

        </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color: red">{{$student->matricule }}</td>
                <td>{{$student->full_name }}</td>
                <td>{{date('d F Y',strtotime($student->date_of_birth)) }}</td>
                <td>{{$student->place_of_birth }}</td>
                <td>{{\App\Setting::getRegionNameByCode($student->region_of_origin) }}</td>
                <td>{{$student->father_address }}</td>
                <td>{{$student->mother_address }}</td>
                <td>{{$student->tutor_name }}</td>
                <td>{{$student->tutor_address }}</td>
                <td>{{ date('d F Y',strtotime($student->admission_date)) }}</td>
                <td>{{\App\Program::getCycleNameByCode($student->programs_program_code)}}</td>
                <td>{{ \App\SchoolSection::getSectionNameByCode($student->sections_section_code) }}</td>
            </tr>
        </tbody>
    </table>
</div><br>
