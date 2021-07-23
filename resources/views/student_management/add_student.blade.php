@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/flatpickr/flatpickr.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('student_management/add_student.add_student_header')
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
    {!! $success_alert !!}
    <br>
    <div class="card">
        <div class="profile__info" style="width: 100%;">
            <div class="profile__info" style="width: 100%;">
                <div class="card-demo">
                    <div class="card bg-green card--inverse">
                        <div class="card-body c-ewangclarks ">
                            <h3 class="card-text text-white text-center">
                                {{ trans('student_management/add_student.add_student_title') }}
                            </h3>
                        </div>
                    </div>
                </div>
        <div class="profile">
            <div class="profile__img">
                <img src="{{asset(trans('img/img.student_p'))}}" alt="" height="300px;" width="400px">
            </div>
                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.add_student')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group" style="position: relative;top: 14px;">
                                        <label for="full-name"
                                               style="color: black;">@lang('student_management/add_student.full_name')</label>
                                        <input type="text" name="full-name" class="form-control" id="full-name">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group" style="position: relative;top: 14px;">
                                        <label for="place-of-birth"
                                               style="color: black;">@lang('student_management/add_student.place_of_birth')</label>
                                        <input type="text" name="place-of-birth" class="form-control"
                                               id="place-of-birth">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="p-2" style="color: black;"
                                           for="date-of-birth">@lang('student_management/add_student.date_of_birth')<br></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                        </div>
                                        <input type="date" class="form-control hidden-md-up"
                                               placeholder="@lang('student_management/add_student.pick_a_date')">
                                        <input type="text" class="form-control date-picker hidden-sm-down"
                                               name="date-of-birth" id="date-of-birth"
                                               placeholder="@lang('student_management/add_student.pick_a_date')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="region-of-origin"
                                               class="p-2"
                                               style="color: black;">@lang('student_management/add_student.select_region')</label>
                                        <select class="select2" name="region-of-origin" id="region-of-origin">
                                            {!! \App\Setting::getRegionList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group" style="position: relative;top: 14px;">
                                        <label for="father-phone"
                                               style="color: black;">@lang('student_management/add_student.father_address')</label>
                                        <input type="number" name="father-phone" class="form-control"
                                               id="place-of-birth">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group" style="position: relative;top: 14px;">
                                        <label for="mother-phone"
                                               style="color: black;">@lang('student_management/add_student.mother_address')</label>
                                        <input type="number" name="mother-phone" class="form-control">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group" style="position: relative;top: 14px;">
                                        <label for="tutor-name"
                                               style="color: black;">@lang('student_management/add_student.tutor_name')</label>
                                        <input type="text" name="tutor-name" class="form-control"
                                               id="tutor-name">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group" style="position: relative;top: 14px;">
                                        <label for="tutor-phone"
                                               style="color: black;">@lang('student_management/add_student.tutor_address')</label>
                                        <input type="number" name="tutor-phone" class="form-control" id="tutor-phone">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="p-2" style="color: black;"
                                           for="admission-date">@lang('student_management/add_student.admission_date')<br></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                        </div>
                                        <input type="date" class="form-control hidden-md-up"
                                               placeholder="@lang('student_management/add_student.pick_a_date')">
                                        <input type="text" class="form-control date-picker hidden-sm-down"
                                               name="admission-date" id="admission-date"
                                               placeholder="@lang('student_management/add_student.pick_a_date')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('student_management/add_student.select_class')</label>
                                        <select class="select2" name="class-code">
                                            {!! \App\AcademicLevel::getClassList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('student_management/add_student.select_program')</label>
                                        <select class="select2" name="program-code">
                                            {!! \App\Program::getProgramsList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <p style="color: black;">@lang('student_management/add_student.select_file')<br>
                                            <label for="file-name" class="text-center"
                                                   style="padding-top:5px;padding-bottom: 0px;padding-left:15px;margin: 0px;color: red;font-size: 10px;"
                                                   id="file-text">@lang('student_management/add_student.select_file_text')</label>
                                        <hr>
                                        <input type="file" class="vims-file-input" name="student-picture" value=""
                                               id="file-name">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="position: relative;left: 35%;">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               class="p-2"
                                               style="color: black;">@lang('student_management/add_student.select_series')</label>
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
                                            class="zmdi zmdi-file-add"></i>@lang('actions/action.submit')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="card-body">
            {!! $student_list !!}
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
            <script src="{{ asset('js/power-school.js') }}"></script>




            <script type="text/javascript">
                var catName = '#' + "<?php echo trans('authorization/category.student_management') ?>";
                var privName = '#' + "<?php echo trans('authorization/privilege.add_student')?>";
                catId = catName.replace(/ /g, "_");
                privId = privName.replace(/ /g, "_");

                $(privId).addClass('navigation__active');
                $(catId).addClass('navigation__sub--active navigation_sub--toggled');

                $('#file-name').change(function () {
                    var fileText = "<?php echo trans('student_management/add_student.file_selected') ?>";
                    $('#file-text').html(fileText.toString());
                    $('#file-text').css({"color": "blue"});
                });
            </script>
@endsection
