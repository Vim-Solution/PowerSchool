
{!! $success_alert !!}
<div class="card">
    <div class="profile">
        <div class="profile__img">
            <img src="{{ asset(trans('img/img.book_logo_p')) }}" alt="" height="300px;" width="400px">
        </div>
        <div class="profile__info" style="width: 100%;">
            <h3 style="color: #0D0A0A;position: relative;left: 20%;">
                <b>{{ trans('series_management/manage_subject_series.manage_subject_series_t') }}</b></h3><br>
            <form method="post" enctype="multipart/form-data"
                  action="{{ trans('settings/routes.manage_subject_series') . trans('settings/routes.get_subject')}}">
                @csrf
                <ul class="icon-list">
                    <li>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="subject-title"
                                           style="color: black;">@lang('series_management/manage_subject_series.subject_title')</label>
                                    <input type="text" class="form-control" name="subject-title" value="{{ $subject->subject_title }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="series-code"
                                           style="color: black;">@lang('series_management/manage_subject_series.select_old_series')</label>
                                    <select class="select2" name="old-series-code">
                                        {!! \App\Series::getSeriesDBList() !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="series-code"
                                           style="color: black;">@lang('series_management/manage_subject_series.select_new_series')</label>
                                    <select class="select2" name="new-series-code">
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
        <h4 class="text-center"> <b>{{ trans('series_management/manage_subject_series.manage_subject_series_title') }}</b></h4>
        <div class="table-responsive">
            <table id="@lang('general.data_table')" class="table table-striped">
                <thead class="thead-default c-ewangclarks text-white">
                <tr>
                    <th>@lang('series_management/manage_subject_series.series_name')</th>
                    <th>@lang('series_management/manage_subject_series.class')</th>
                    <th>@lang('series_management/manage_subject_series.subject_code')</th>
                    <th>@lang('series_management/manage_subject_series.subject_title')</th>
                    <th>@lang('series_management/manage_subject_series.coefficient')</th>
                    <th>@lang('series_management/manage_subject_series.subject_weight')</th>
                    <th>@lang('series_management/manage_subject_series.cycle_name')</th>
                    <th>@lang('series_management/manage_subject_series.section_name')</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="color: red">{!! \App\Subject::getSubjectSeriesListById($subject->subject_id) !!}</td>
                    <td>{{ \App\AcademicLevel::getClassNameByCode($subject->classes_class_code)}}</td>
                    <td style="color: red">{{$subject->subject_code }}</td>
                    <td>{{ $subject->subject_title  }}</td>
                    <td>{{$subject->coefficient }}</td>
                    <td>{{$subject->subject_weight }}</td>
                    <td>{{\App\Program::getCycleNameByCode($subject->programs_program_code)}}</td>
                    <td>{{ \App\SchoolSection::getSectionNameByCode($subject->sections_section_code) }}</td>
                </tr>
                </tbody>
            </table>
        </div><br>
    </div>
</div>
