<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="@lang('general.data_table')" class="table table-striped">
                <thead class="thead-default c-ewangclarks text-white">
                <tr>
                    <th>@lang('student_management/edit_student.matricule')</th>
                    <th>@lang('student_management/edit_student.full_name')</th>
                    <th>@lang('student_management/edit_student.program_name')</th>
                    <th>@lang('student_management/edit_student.admission_date')</th>
                    <th>@lang('student_management/edit_student.action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                    <tr>
                        <td style="color: red">{{$student->matricule }}</td>
                        <td>{{$student->full_name }}</td>
                        <td>{{\App\Program::getCycleNameByCode($student->programs_program_code) }}</td>
                        <td>{{ date('F d, Y',strtotime($student->admission_date)) }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ trans('settings/routes.edit_student'). '/' . \App\Encrypter::encrypt( $student->student_id) }}"
                                   class="btn btn-info">
                                    <i class="zmdi zmdi-edit zmdi-hc-fw"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
