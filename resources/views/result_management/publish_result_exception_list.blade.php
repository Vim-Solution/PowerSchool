@if(!session('status'))
   <h3></h3> <br>
{!! \App\Setting::getAlertFailure(trans('result_management/publish_result.information',['year' => \App\Setting::getAcademicYear()])) !!}</b>
@endif
@php
    $academicYear = App\Setting::getAcademicYear();
    $sequence = App\Setting::getSequence();
    $teacherId = \Illuminate\Support\Facades\Auth::user()->user_id;
@endphp

<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped" style="width: 100%;">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('result_management/publish_result.class')</th>
            <th>@lang('result_management/publish_result.subject_code')</th>
            <th>@lang('result_management/publish_result.subject_title')</th>
            <th>@lang('result_management/publish_result.teachers')</th>
            <th>@lang('result_management/publish_result.teachers_n')</th>
            <th>@lang('result_management/publish_result.notification')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($unsubmitted_subjects as $subject)
            <tr>
                <td style="color: red">{!! \App\AcademicLevel::getClassNameByCode($subject->classes_class_code) !!}</td>
                <td>{{$subject->subject_code }}</td>
                <td>{{ $subject->subject_title  }}</td>
                <td>{!!  \App\Subject::getSubjectTeachersList($subject->subject_id,$academicYear) !!}</td>
                <td style="color: red">{!! \App\Subject::getTeachersListWithMarksNotSubmitted($subject->subject_id,$sequence->sequence_id,$academicYear) !!}</td>
                <td style="width: 20%;">
                    <a class="btn bg-green text-white"
                       href="{{ trans('settings/routes.publish_result')  . trans('settings/routes.notify') . '/' . \App\Encrypter::encrypt($subject->subject_id) }}"><i
                            class="zmdi zmdi-notifications-add"></i> @lang('actions/action.notify')</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div><br>
