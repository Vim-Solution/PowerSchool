<h3 class="text-center">
    <b>{{ trans('subject_management/manage_test.result_list_header',['sequence' => \App\Setting::getSequenceName(),'class' => \App\AcademicLevel::getClassNameByCode($subject->classes_class_code),'subject' => $subject->subject_title]) }}</b>
</h3>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('subject_management/manage_test.name')</th>
            <th>@lang('subject_management/manage_test.matricule')</th>
            <th>@lang('subject_management/manage_test.score')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            @php
                $student =$students->where(trans('database/table.student_id'),$result->students_student_id)->first();
                if(!empty($student)){
                  $mark = $result->subject_score;
                  $name = $student->full_name;
                  $matricule = $student->matricule;
                }else{
                 $name = null;
                  $matricule = null;
                $mark = 0;
                }
            @endphp
            @if(!empty($name) && !empty($matricule))
            <tr>
                <td >{!! $name !!}</td>
                <td>{{$matricule }}</td>
                <td style="color: red">{{ $mark . '/' . $subject->subject_weight }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div><br>
<a href="{{  trans('settings/routes.manage_subject_test') }}"
   class="btn c-ewangclarks" style="width: 30%;position: relative;left: 70%;">
    <h6 class="text-white"><i
            class="zmdi zmdi-arrow-back"></i>@lang('subject_management/manage_test.return_back')
    </h6>
</a><br><br>

