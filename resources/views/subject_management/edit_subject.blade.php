@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('subject_management/edit_subject.edit_subject_header')
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
    <div class="card new-contact">
        <a href="{{ trans('settings/routes.edit_subject') }}" class="btn c-ewangclarks btn--icon"
           style="position: relative;left:95%;"> <i class="zmdi zmdi-arrow-back"></i> </a>
        <div class="container">
            <div class="card-body">
                <div class="card-demo">
                    <div class="card bg-green card--inverse">
                        <div class="card-body c-ewangclarks ">
                            <h3 class="card-text text-white text-center">
                                <i class="zmdi zmdi-edit"></i> @lang('subject_management/edit_subject.edit_subject_header')
                            </h3>
                        </div>
                    </div>
                </div>
                <form method="get"
                      action="{{ trans('settings/routes.edit_subject') . trans('settings/routes.save_subject') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/edit_subject.subject_code')</label>
                                <input type="text" class="form-control" name="subject-code"
                                       placeholder="@lang('subject_management/edit_subject.subject_code_placeholder')"
                                       value="{{ $subject->subject_code }}" readonly>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/edit_subject.subject_title')</label>
                                <input type="text" name="subject-title" class="form-control"
                                       placeholder="@lang('subject_management/add_subject.subject_title_placeholder')"
                                       value="{{ $subject->subject_title }}" required>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/edit_subject.coefficient')</label>
                                <input type="text" name="coefficient" class="form-control input-mask" data-mask="00"
                                       placeholder="eg: 05" value="{{ $subject->coefficient }}" required>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-sm-4 col-md-3">
                                    <label>@lang('subject_management/edit_subject.active_state')</label>
                                    <br>
                                    <div class="form-group">
                                        <div class="toggle-switch">
                                            <input type="checkbox" class="toggle-switch__checkbox" id="statebtn"
                                                   name="state">
                                            <i class="toggle-switch__helper"></i>
                                        </div>
                                    </div>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/edit_subject.select_class')</label>
                                <select class="select2" name="class-code" id="class-code" required>
                                    {!! \App\AcademicLevel::getClassList() !!}
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.series_code')</label>
                                <select class="select2" multiple data-placeholder="Select one or more choices"
                                        name="series-code[]" id="series-code">
                                    {!! \App\Series::getSeriesList() !!}
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/edit_subject.subject_weight')</label>
                                <input type="text" name="subject-weight" class="form-control input-mask" data-mask="00"
                                       placeholder="eg: 20" value="{{$subject->subject_weight}}" required>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/edit_subject.select_program')</label>
                                <select class="select2" name="program" id="program" required>
                                    {!! \App\Program::getProgramsList() !!}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="position: relative;left: 22%;">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="academic-year"
                                       style="color: black;">@lang('subject_management/edit_subject.academic_year')</label>
                                <select class="select2" name="academic-year" id="academic-year" required>
                                    {!! \App\AcademicLevel::getAcademicYearList() !!}
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="mt-5 text-center">
                        <button type="submit" class="btn c-ewangclarks" style="width: 30%;"><i
                                class="zmdi zmdi-refresh-sync"></i> @lang('subject_management/edit_subject.edit_subject_button')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.edit_subject')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('#class-code').val("<?php echo $subject->classes_class_code ?>");
        $('#section').val("<?php echo $subject->sections_section_code ?>");
        $('#program').val("<?php echo $subject->programs_program_code ?>");
        $('#academic-year').val("<?php echo $subject->academic_year ?>");

        if ("1" == "<?php echo $subject->state ?>") {
            $('input[type="checkbox"]').prop('checked', true);
        } else {
            $('input[type="checkbox"]').prop('checked', false);
        }

        var serieC = new Array();
        var i = 0;

        @foreach($seriesName as $seriesCode)
        $('#series-code').val("<?php echo $seriesCode ?>");
        serieC[i] = "<?php echo $seriesCode ?>";
        i++;
        @endforeach


        $('#series-code').val(serieC);

    </script>
@endsection
