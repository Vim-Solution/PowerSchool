<div class="card profile">
    <div class="profile__img">
        <img src="{{asset($student->profile)}}" alt="">

        <a href="#" class="zmdi zmdi-camera profile__img__edit"></a>
    </div>

    <div class="profile__info">
        <p style="color: #0D0A0A;"><b>{{ trans('student_portal/student_info.academic_setting') }}</b></p>
        <ul class="icon-list">
            <li><i class="zmdi zmdi-male-female"></i> @lang('student_portal/student_info.full_name')
                : {{ ucfirst(strtolower($student->full_name)) }}</li>
            <li style="color: red"><i
                    class="zmdi zmdi-graduation-cap"></i>@lang('student_portal/student_info.matricule')
                : {{ $student->matricule }}</li>
            <li>
                <i class="zmdi zmdi-calendar"></i>@lang('student_portal/student_info.class')
                : {{ \App\Student::getStudentClassNameByMatricule($student->matricule) }}
            </li>
            @if($student->programs_program_code == trans('settings/setting.al'))
                <li>
                    <i class="zmdi zmdi-pin-drop"></i> @lang('student_portal/student_info.series_name')
                    : {{ \App\Student::getStudentSeriesNameByMatricule($student->matricule)}}
                </li>
            @endif
            <li><i class="zmdi zmdi-pin"></i>@lang('student_portal/student_info.cycle_name')
                : {{ \App\Program::getCycleNameByCode($student->programs_program_code) }}
            </li>
            <li>
                <i class="zmdi zmdi-pin-drop"></i> @lang('student_portal/student_info.section_name')
                : {{ \App\SchoolSection::getSectionNameByCode($student->sections_section_code) }}
            </li>
        </ul>
    </div>
</div>

<div class="toolbar">
    <nav class="toolbar__nav">
        <h6 class="active">@lang('student_portal/student_info.general_info')</h6>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-body__title mb-4">@lang('student_portal/student_info.basic_info')</h4>

        <ul class="icon-list">
            <li><i class="zmdi zmdi-phone"></i> @lang('student_portal/student_info.full_name')
                : {{ $student->full_name }}</li>
            <li><i class="zmdi zmdi-calendar-alt"></i> @lang('student_portal/student_info.date_of_birth')
                : {{ date('d F Y',strtotime($student->date_of_birth)) }}</li>
            <li><i class="zmdi zmdi-my-location"></i> @lang('student_portal/student_info.place_of_birth')
                : {{ $student->place_of_birth }}</li>
            <li><i class="zmdi zmdi-globe-alt"></i> @lang('student_portal/student_info.region_of_origin')
                : {{ \App\Setting::getRegionNameByCode($student->region_of_origin) }}</li>
            <li><i class="zmdi zmdi-calendar"></i> @lang('student_portal/student_info.admission_date')
                : {{ date('d,F Y', strtotime($student->admission_date)) }}</li>
        </ul>
        <br><br>

        <h4 class="card-body__title mb-4">@lang('student_portal/student_info.school_info')</h4>

        <ul class="icon-list">
            <li><i class="zmdi zmdi-phone"></i> @lang('student_portal/student_info.father_address')
                : {{ $student->father_address }}</li>
            <li><i class="zmdi zmdi-phone"></i> @lang('student_portal/student_info.mother_address')
                : {{ $student->mother_address  }}</li>
            <li><i class="zmdi zmdi-male"></i> @lang('student_portal/student_info.tutor_name')
                : {{ $student->tutor_name}}</li>
            <li><i class="zmdi zmdi-globe"></i> @lang('student_portal/student_info.tutor_address')
                : {{ $student->tutor_address  }}</li>
        </ul>

    </div>
</div>

@if((date('d m',strtotime($student->date_of_birth)) == date('d m')) && ($student->date_of_birth !=''))
    <script type="text/javascript">
      $(document).ready(function() {
          var message = "<?php echo(trans('student_portal/student_info.happy_birthday', ['name' => $student->full_name])) ?>"
          notify('bottom', 'left', 'fa fa-comment', 'success', 'animated fadeInLeft', 'animated fadeOutLeft',message);
      });
    </script>
@endif
