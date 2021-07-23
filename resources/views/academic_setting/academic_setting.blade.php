@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/flatpickr/flatpickr.min.css') }}"/>
@endsection

@section('title')
    @lang('academic_setting/academic_setting.academic_setting_header')
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('status'))
        {!! session('status') !!}
    @endif
    <br>
    <div class="card">
        <div class="profile">
            <div class="profile__img">
                <img src="{{asset(trans('img/img.series_ladder'))}}" alt="" height="300px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 20%;">
                    <b>{{ trans('academic_setting/academic_setting.academic_setting_t') }}</b></h3><br>
                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.academic_setting')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="sequence-code"
                                               style="color: black;">@lang('academic_setting/academic_setting.select_sequence')</label>
                                        <select class="select2" name="sequence-code" id="sequence-code">
                                            {!! \App\Sequence::getSequenceList()!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="academic-year"
                                                   style="color: black;">@lang('academic_setting/academic_setting.select_year')</label>
                                            <select class="select2" name="academic-year" id="academic-year">
                                                {!! \App\Setting::getDefaultAcademicYearsList() !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-sm-4">
                                <label class="" style="color: black;"
                                       for="mark-submission-date">@lang('academic_setting/academic_setting.select_publish_date')<br></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control hidden-md-up"
                                           placeholder="@lang('student_management/add_student.pick_a_date')">
                                    <input type="text" class="form-control date-picker hidden-sm-down"
                                           name="mark-submission-date" id="mark-submission-date"
                                           placeholder="@lang('student_management/add_student.pick_a_date')">
                                </div>
                            </div>
                            </div>
                        </li>
                        <li>
                            <button type="submit" class="btn c-ewangclarks"
                                    style="width: 50%;position: relative;left: 16%;">
                                <h6 class="text-white"><i
                                            class="zmdi zmdi-arrow-forward"></i>@lang('actions/action.submit')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>

        <div class="card-body">
            {!! $academic_settings !!}
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/vendors/flatpickr/flatpickr.min.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.academic_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.academic_setting')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection
