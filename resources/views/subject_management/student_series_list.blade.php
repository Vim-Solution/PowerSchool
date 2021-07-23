<h4 class="text-center">
    <b>{{ trans('subject_management/series_data_upload.series_data_upload_result_title') . $class->class_name .' '.\App\Setting::getAcademicYear() }}</b>
</h4>
<br>
<div class="table-responsive">
    <table id="data-table" class="table table-dark mb-0">
        <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('subject_management/series_data_upload.student_matricule')</th>
                <th>@lang('subject_management/series_data_upload.student_name')</th>
                <th>@lang('subject_management/series_data_upload.series_code')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($series_students as $series_student)
            <tr>
                <td>{{$series_student->matricule }}</td>
                <td>{{ \App\Student::getStudentNameByMatricule($series_student->matricule) }}</td>
                <td>{{$series_student->series_series_code }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div><br>


