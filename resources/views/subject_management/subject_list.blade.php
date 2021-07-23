<h3 class="text-center">{{ $list_title }}</h3><br>
<h4 class="text-center">
    <b>{{ trans('subject_management/manage_test.manage_subject_list_title',['year' => \App\Setting::getAcademicYear()]) }}</b>
</h4>
@php
    $academicYear = App\Setting::getAcademicYear();
    $sequence = App\Setting::getSequence();
    $teacherId = \Illuminate\Support\Facades\Auth::user()->user_id;
@endphp

@if(\App\Subject::hasMarkEntryByTeacherId($teacherId,$academicYear))

    <a href="{{  trans('settings/routes.result_list') }}"
       class="btn c-ewangclarks" style="width: 30%;position: relative;left: 70%;">
        <h6 class="text-white"><i
                class="zmdi zmdi-view-list"></i>@lang('subject_management/manage_test.view_result_list')
        </h6>
    </a>
@endif
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped" style="width: 100%;">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('subject_management/manage_test.class')</th>
            <th>@lang('subject_management/manage_test.subject_code')</th>
            <th>@lang('subject_management/manage_test.subject_title')</th>
            <th>@lang('subject_management/manage_test.coefficient')</th>
            <th>@lang('subject_management/manage_test.subject_weight')</th>
            <th>@lang('subject_management/manage_test.mark_entry_state')</th>
            <th>@lang('subject_management/manage_test.operation')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teacherSubjects as $subject)
            <tr>
                <td style="color: red">{!! \App\AcademicLevel::getClassNameByCode($subject->classes_class_code) !!}</td>
                <td style="color: red">{{$subject->subject_code }}</td>
                <td>{{ $subject->subject_title  }}</td>
                <td>{{$subject->coefficient }}</td>
                <td>{{$subject->subject_weight }}</td>
                <td>{{\App\Subject::getSubjectMarkEntryState($subject->subject_id,$teacherId,$sequence->sequence_id,$academicYear)}}</td>
                <td style="width: 20%;">
                    <a class="btn bg-red  text-white"
                       href="{{ trans('settings/routes.manage_subject_test')  . '/' . \App\Encrypter::encrypt($subject->subject_id) }}"><i
                            class="zmdi zmdi-store"></i> @lang('subject_management/manage_test.manage_test')</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div><br>
@if(\App\Subject::hasMarkEntryByTeacherId($teacherId,$academicYear))

    <a href="{{  trans('settings/routes.result_list') }}"
       class="btn c-ewangclarks" style="width: 30%;position: relative;left: 35%;">
        <h6 class="text-white"><i
                class="zmdi zmdi-view-list"></i>@lang('subject_management/manage_test.view_result_list')
        </h6>
    </a><br><br>
@endif
