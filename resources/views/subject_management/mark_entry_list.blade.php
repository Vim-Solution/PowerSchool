<br>
<h4 class="text-center">
    <b>{{ trans('subject_management/manage_test.student_list_title',['year' => \App\Setting::getAcademicYear(),'class' => \App\AcademicLevel::getClassNameByCode($subject->classes_class_code),'subject' => $subject->subject_title,'test' => $test->test_name]) }}</b>
</h4>
<form method="post" enctype="multipart/form-data"
      action="{{  trans('settings/routes.manage_subject_test') . trans('settings/routes.mark_entry') . '/' . \App\Encrypter::encrypt($test->test_id)}}">
   @csrf()
    <button type="submit" class="btn bg-green"
            style="width: 30%;position: relative;left: 70%;">
        <h6 class="text-white"><i
                class="zmdi zmdi-save"></i>@lang('actions/action.save_marks')</h6>
    </button><br>
    <div class="table-responsive">
        <table id="@lang('general.data_table')" class="table table-striped">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('subject_management/manage_test.matricule')</th>
                <th>@lang('subject_management/manage_test.name')</th>
                <th>@lang('subject_management/manage_test.enter_marks')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $student)
                <tr>
                    <td style="color: red">{{$student->full_name }}</td>
                    <td>{{ $student->matricule  }}</td>
                    <td>
                        <div class="form-group" style="background-color: white">
                            <input type="text" class="form-control" name="{{ $student->matricule }}" id="{{ $student->matricule }}" value="{{ $testScores->where(trans('database/table.students_student_id'),$student->student_id)->isEmpty() ? 0.0 :$testScores->where(trans('database/table.students_student_id'),$student->student_id)->first()->test_score }}">
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br>
    <button type="submit" class="btn bg-green"
            style="width: 50%;position: relative;left: 20%;">
        <h6 class="text-white"><i
                class="zmdi zmdi-save"></i>@lang('actions/action.save_marks')</h6>
    </button>
    <br><br>
</form>
<a class="btn bg-red  text-white" style="position: relative;left: 68%;width: 30%;"
   href="{{ trans('settings/routes.manage_subject_test')  . '/' . \App\Encrypter::encrypt($subject->subject_id) }}"><i
        class="zmdi zmdi-arrow-back"></i> @lang('subject_management/manage_test.change_test')</a><br><br><br>


