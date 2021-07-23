<br><br>
<h4 class="text-center">
    <b>{{ trans('student_management/student_portal_access.student_list_title',['year' => $academicYear])  }}</b>
</h4>
<br>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('student_management/student_portal_access.full_name')</th>
            <th>@lang('student_management/student_portal_access.matricule')</th>
            <th>@lang('student_management/student_portal_access.secret')</th>
            <th>@lang('student_management/student_portal_access.suspension_state')</th>
            <th>@lang('student_management/student_portal_access.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($students as $student)
            <tr>
                <td>{{$student->full_name }}</td>
                <td>{{$student->matricule }}</td>
                @php
                    $secret_code = $secret_codes->where(trans('database/table.matricule'),$student->matricule)->first();
                    if(!empty($secret_code)){
                       $code = \App\Encrypter::decrypt($secret_code->secret_code);
                    }else{
                       $code= null;
                    }
                @endphp
                <td>{{ $code }}</td>
                <td>
                    @if($student->academic_state ==1)
                        @lang('student_management/student_portal_access.not_suspended')
                    @else
                        @lang('student_management/student_portal_access.suspended')
                    @endif
                </td>
                <td>
                        @if($student->academic_state ==1)
                            <a href="{{trans('settings/routes.student_suspension') . '/' . \App\Encrypter::encrypt($student->matricule) }}"
                               class="btn bg-red"><h6 class="text-white" style="font-size: 12px;"><i
                                        class="zmdi zmdi-lock"></i>{{trans('student_management/student_portal_access.suspend_student')}}
                                </h6></a>
                        @else
                            <a href="{{trans('settings/routes.revert_student_suspension') . '/' . \App\Encrypter::encrypt($student->matricule) }}"
                               class="btn btn-primary"><h6 class="text-white" style="font-size: 12px;"><i
                                        class="zmdi zmdi-lock-open"></i>{{trans('student_management/student_portal_access.revert_student_suspension')}}
                                </h6></a>
                        @endif
                        <a href="{{trans('settings/routes.generate_secrete') . '/' . \App\Encrypter::encrypt($student->matricule) }}"
                           class="btn bg-green "><h6 class="text-white" style="font-size: 12px;"><i
                                    class="zmdi zmdi-lock-outline"></i>{{trans('student_management/student_portal_access.generate_secret')}}
                            </h6></a>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div><br>
