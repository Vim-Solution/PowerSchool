<h4 class="text-center">
    <b>{{ trans('subject_management/get_class_list.get_class_list_result_title') . \App\AcademicLevel::getClassNameByCode($classCode) .' '.$academic_year }}</b>
</h4>
<br>
<div class="table-responsive">
    <table id="data-table" class="table table-striped mb-0">
        <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>S/N</th>
                <th>@lang('subject_management/get_class_list.student_name')</th>
                <th>@lang('subject_management/get_class_list.mat_number')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($student_lists as $student_list)
            <tr>
                <th scope="row">{{++$sn}}</th>
                <td>{{ \App\Student::getStudentNameByMatricule($student_list->matricule) }}</td>
                <td>{{$student_list->matricule }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div><br>


