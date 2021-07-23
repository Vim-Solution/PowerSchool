@php
    $academicYear = App\Setting::getAcademicYear();
    $sequence = App\Setting::getSequence();
    $userId = \Illuminate\Support\Facades\Auth::user()->user_id;
@endphp

@if(\App\Setting::hasPublishDatePass(\Illuminate\Support\Facades\Auth::user()->sections_section_code) ||(\App\Subject::getSubjectMarkEntryState($subject->subject_id,$userId,$sequence->sequence_id,$academicYear) == trans('subject_management/manage_test.marks_submitted') || \App\Subject::getSubjectMarkEntryState($subject->subject_id,$userId,$sequence->sequence_id,$academicYear) == trans('subject_management/manage_test.general_marks_submitted')))
    <div class="alert alert-info alert-dismissable">
        @lang('subject_management/manage_test.publish_date_pass')
        <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <h3 class="text-center">{{    $list_title = $sequence->sequence_name . ' ' . App\AcademicLevel::getClassNameByCode($subject->classes_class_code)  . ' ' . $subject->subject_title . ' ' . trans('subject_management/manage_test.test_list') }}</h3>
    <br>

    <div class="table-responsive table-danger bg-gray">
        <a href="# "
           class="btn btn-success disabled"
           style="width: 30%;position: relative;left: 70%;">
            <h6 class="text-white"><i
                    class="zmdi zmdi-cloud-download"></i>@lang('subject_management/manage_test.submit_test')
            </h6>
        </a>
        <table id="@lang('general.data_table')" class="table table-striped">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('subject_management/manage_test.test_code')</th>
                <th>@lang('subject_management/manage_test.test_name')</th>
                <th>@lang('subject_management/manage_test.test_weight')</th>
                <th>@lang('subject_management/manage_test.action')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sequenceSubjectTests as $test)
                <tr>
                    <td style="color: red;">{{ $test->test_code  }}</td>
                    <td>{{ $test->test_name }}</td>
                    <td>{{$test->test_weight }}</td>
                    <td>
                        <a class="btn bg-cyan  disabled text-white"
                           href="#"><i
                                class="zmdi zmdi-store"></i> @lang('subject_management/manage_test.upload_marks')</a>
                        <a class="btn bg-blue  disabled text-white"
                           href="#"><i
                                class="zmdi zmdi-store"></i> @lang('subject_management/manage_test.upload_marks_csv')
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
        <br>
        <a href="#"
           class="btn btn-success disabled"
           style="width: 40%;position: relative;left: 30%;">
            <h6 class="text-white"><i
                    class="zmdi zmdi-cloud-download"></i>@lang('subject_management/manage_test.submit_test')
            </h6>
        </a>
    </div><br><br><br><br>
@else
    <div class="alert alert-success" >
       {{ trans('subject_management/manage_test.publish_date',['sequence' =>  $sequence->sequence_name,'date' => date('F d, Y',strtotime(\App\Setting::getPublishDate(\Illuminate\Support\Facades\Auth::user()->sections_section_code)))])}}
    </div>
    <h3 class="text-center">{{    $list_title = $sequence->sequence_name . ' ' . App\AcademicLevel::getClassNameByCode($subject->classes_class_code)  . ' ' . $subject->subject_title . ' ' . trans('subject_management/manage_test.test_list') }}</h3>
    <br>

    <div class="table-responsive">
        <a href="{{  trans('settings/routes.submit_mark') }}"
           class="btn btn-success"
           style="width: 30%;position: relative;left: 70%;">
            <h6 class="text-white"><i
                    class="zmdi zmdi-cloud-download"></i>@lang('subject_management/manage_test.submit_test')
            </h6>
        </a>
        <table id="@lang('general.data_table')" class="table table-striped">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('subject_management/manage_test.test_code')</th>
                <th>@lang('subject_management/manage_test.test_name')</th>
                <th>@lang('subject_management/manage_test.test_weight')</th>
                <th>@lang('subject_management/manage_test.mark_entry_state')</th>
                <th>@lang('subject_management/manage_test.action')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sequenceSubjectTests as $test)
                <tr>
                    <td style="color: red;">{{ $test->test_code  }}</td>
                    <td>{{ $test->test_name }}</td>
                    <td>{{$test->test_weight }}</td>
                    <td>{{ \App\TestManager::getTestMarkEntryStateByAcademicYear($test->test_id,\App\Setting::getAcademicYear()) }}</td>
                    <td>
                        <a class="btn bg-cyan  text-white"
                           href="{{ trans('settings/routes.manage_subject_test')  . trans('settings/routes.mark_entry') . '/' . \App\Encrypter::encrypt($test->test_id) }}"><i
                                class="zmdi zmdi-store"></i> @lang('subject_management/manage_test.upload_marks')</a>
                        <a class="btn bg-blue  text-white"
                           href="{{ trans('settings/routes.manage_subject_test') . trans('settings/routes.csv_mark_entry') . '/' . \App\Encrypter::encrypt($test->test_id) }}"><i
                                class="zmdi zmdi-store"></i> @lang('subject_management/manage_test.upload_marks_csv')
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
        <br>
        <a href="{{ trans('settings/routes.submit_mark') }}"
           class="btn btn-success"
           style="width: 40%;position: relative;left: 30%;">
            <h6 class="text-white"><i
                    class="zmdi zmdi-cloud-download"></i>@lang('subject_management/manage_test.submit_test')
            </h6>
        </a>
    </div><br><br><br><br>

@endif
@if($subjectTests->isNotEmpty())
    @php
        $sequenceIds =$subjectTests->pluck(trans('database/table.sequences_sequence_id'))->unique()->sortBy('asc');
    @endphp
    @foreach($sequenceIds as $sequenceId)
        <h3 class="text-center">{{    $list_title = \App\Sequence::getSequenceNameById($sequenceId) . ' ' . App\AcademicLevel::getClassNameByCode($subject->classes_class_code)  . ' ' . $subject->subject_title . ' ' . trans('subject_management/manage_test.test_list') }}</h3>
        <br>
        <div class="table-responsive">
            <table id="@lang('general.data_table')" class="table table-striped">
                <thead class="thead-default c-ewangclarks text-white">
                <tr>
                    <th>@lang('subject_management/manage_test.test_code')</th>
                    <th>@lang('subject_management/manage_test.test_name')</th>
                    <th>@lang('subject_management/manage_test.test_weight')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($subjectTests->where(trans('database/table.sequences_sequence_id'),$sequenceId) as $test)
                    <tr>
                        <td style="color: red;">{{ $test->test_code  }}</td>
                        <td>{{ $test->test_name }}</td>
                        <td>{{$test->test_weight }}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div><br>
    @endforeach
@endif
