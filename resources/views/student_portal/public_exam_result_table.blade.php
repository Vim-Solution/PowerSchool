<b style="color: red;font-size: 12px;">{{ trans('student_portal/result_portal.gce_instructions') }}</b></h4><br><br>
<h4 class="text-center">
    @if($examSetting->programs_program_code == trans('student_portal/result_portal.gce_ol_code'))
        <b>{{ trans('student_portal/result_portal.gce_ol_r') }}</b></h4>
@elseif($examSetting->programs_program_code == trans('student_portal/result_portal.gce_al_code'))
    <b>{{ trans('student_portal/result_portal.gce_al_r') }}</b></h4>
@endif
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('student_portal/result_portal.center_no_title')</th>
            <th>@lang('student_portal/result_portal.matricule')</th>
            <th>@lang('student_portal/result_portal.class')</th>
            <th>@lang('student_portal/result_portal.full_name')</th>
            <th>@lang('student_portal/result_portal.date_of_birth')</th>
            <th>@lang('student_portal/result_portal.cycle_name')</th>
            <th>@lang('student_portal/result_portal.section_name')</th>
            <th>@lang('student_portal/result_portal.exam_type')</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="color: red">{{$examSetting->center_no}}</td>
            <td>{{$student->matricule }}</td>
            <td>{{\App\Student::getStudentClassNameByMatricule($student->matricule)}}</td>
            <td style="color: red">{{$student->full_name }}</td>
            <td>{{date('m F Y',strtotime($student->date_of_birth)) }}</td>
            <td>{{\App\Program::getCycleNameByCode($examSetting->programs_program_code)}}</td>
            <td>{{ \App\SchoolSection::getSectionNameByCode($examSetting->sections_section_code) }}</td>
            @if($examSetting->programs_program_code == trans('student_portal/result_portal.gce_ol_code'))
                <td>{{ trans('student_portal/result_portal.gce_ol')  }}</td>
            @else
                <td>{{ trans('student_portal/result_portal.gce_al')  }}</td>
            @endif
        </tr>
        </tbody>
    </table>
</div><br>
<a href="{{ trans('settings/routes.download_public_exam') . '/' . \App\Encrypter::encrypt($examSetting->id) }}"
   class="btn c-ewangclarks"
   style="width: 40%;position: relative;left: 30%;">
    <h6 class="text-white"><i
                class="zmdi zmdi-cloud-download"></i>@lang('student_portal/result_portal.download_result_text')
    </h6>
</a>
