<br>
<div class="card">
    <div class="profile">
        <div class="profile__img">
            <img src="{{asset($student->profile)}}" alt="" height="300px;" width="400px">
        </div>
        <div class="profile__info" style="width: 100%;">
            <h3 style="color: #0D0A0A;position: relative;left: 20%;">
                <b>{{ trans('series_management/manage_student_series.manage_student_series_header') }}</b></h3><br>
            <form method="post" enctype="multipart/form-data"
                  action="{{ trans('settings/routes.manage_student_series') . trans('settings/routes.get_student')}}">
                @csrf
                <ul class="icon-list">
                    <li>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="student-matricule"
                                           style="color: black;">@lang('series_management/manage_student_series.student_matricule')</label>
                                    <input type="text" class="form-control" name="student-matricule" value="{{ $student->matricule }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="series-code"
                                           style="color: black;">@lang('series_management/manage_student_series.select_series')</label>
                                    <select class="select2" name="series-code">
                                        {!! \App\Series::getSeriesDBList() !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 50%;position: relative;left: 16%;">
                            <h6 class="text-white"><i
                                    class="zmdi zmdi-globe-alt"></i>@lang('actions/action.change_series')</h6>
                        </button>
                        <br><br><br>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <div class="card-body">
        <h4 class="text-center"> <b>{{ trans('series_management/manage_student_series.table_title') }}</b></h4>
       <div class="table-responsive">
            <table id="@lang('general.data_table')" class="table table-striped">
                <thead class="thead-default c-ewangclarks text-white">
                <tr>
                    <th>@lang('series_management/manage_student_series.series_name')</th>
                    <th>@lang('series_management/manage_student_series.class')</th>
                    <th>@lang('series_management/manage_student_series.full_name')</th>
                    <th>@lang('series_management/manage_student_series.date_of_birth')</th>
                    <th>@lang('series_management/manage_student_series.father_address')</th>
                    <th>@lang('series_management/manage_student_series.cycle_name')</th>
                    <th>@lang('series_management/manage_student_series.section_name')</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="color: red">{{ \App\Student::getStudentSeriesNameByMatricule($student->matricule) }}</td>
                    <td>{{\App\Student::getStudentClassNameByMatricule($student->matricule)}}</td>
                    <td style="color: red">{{$student->full_name }}</td>
                    <td>{{date('m F Y',strtotime($student->date_of_birth)) }}</td>
                    <td>{{$student->father_address }}</td>
                    <td>{{\App\Program::getCycleNameByCode($student->programs_program_code)}}</td>
                    <td>{{ \App\SchoolSection::getSectionNameByCode($student->sections_section_code) }}</td>
                </tr>
                </tbody>
            </table>
        </div><br>
    </div>
</div>
