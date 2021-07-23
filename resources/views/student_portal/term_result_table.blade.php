{!! $header !!}
<div class=" card profile">
    <div class="profile__img">
        <img src="{{asset($student->profile)}}" alt="" width="300px;" height="150px;">
    </div>
    <div class="profile__info">
       <ul class="icon-list">
           <li style="color: black;"><i class="zmdi zmdi-store"></i>{{ trans('settings/setting.school_name') }}</li>
           <li><i class="zmdi zmdi-male-female"></i>{{ ucfirst(strtolower($student->full_name)) }}</li>
            <li style="color: red"><i
                    class="zmdi zmdi-graduation-cap"></i>{{ $student->matricule }}</li>
            <li>
                <i class="zmdi zmdi-calendar"></i>{{ \App\Student::getStudentClassNameByMatricule($student->matricule) }}
            </li>
            @if($student->programs_program_code == trans('settings/setting.al'))
                <li>
                    <i class="zmdi zmdi-pin-drop"></i>{{ \App\Student::getStudentSeriesNameByMatricule($student->matricule)}}
                </li>
            @endif
            <li style="color: black">
                <i class="zmdi zmdi-calendar-check"></i>{{ App\Term::getTermNameById($termId) . ' ' . trans('general.result') . ', ' . $year }}
            </li>
        </ul>
    </div>
</div>
<div class="table-responsive">
    <table id="@lang('general.data_table')" class="table table-striped ">
        <thead class="thead-default c-ewangclarks text-white">
        <tr>
            <th>@lang('student_portal/result_portal.subject_title')</th>
            <th>@lang('student_portal/result_portal.subject_weight')</th>
            <th>@lang('student_portal/result_portal.coefficient')</th>
            @foreach($sequences as $sequence)
                <th>{{$sequence->sequence_name}}</th>
            @endforeach
            <th>@lang('student_portal/result_portal.final_mark')</th>
            <th>@lang('student_portal/result_portal.total')</th>

        </tr>
        </thead>
        <tbody>
        {!! $term_result !!}
        </tbody>
    </table>
</div>
<br>

<a href="{{ trans('settings/routes.download_result_t')   . '/' . \App\Encrypter::encrypt($student->student_id). '/' .\App\Encrypter::encrypt($termId) . '/' . \App\Encrypter::encrypt($year) }}"
   class="btn c-ewangclarks"
   style="width: 40%;position: relative;left: 30%;">
    <h6 class="text-white"><i
            class="zmdi zmdi-cloud-download"></i>@lang('student_portal/result_portal.download_result')
    </h6>
</a>
