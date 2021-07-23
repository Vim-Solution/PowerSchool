@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('subject_management/batch_subject_upload.batch_subject_upload_header')
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
        <div class="container">

            <div class="card-body">
                <div class="card-demo">
                    <div class="card bg-green card--inverse">
                        <div class="card-body c-ewangclarks">
                            <h3 class="card-text text-white text-center">
                                @lang('subject_management/add_subject.add_subject_header')
                            </h3>
                        </div>
                    </div>
                </div>
                <form method="post" action="{{ trans('settings/routes.add_subject') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.subject_code')</label>
                                <input type="text" class="form-control" name="subject-code"
                                       placeholder="@lang('subject_management/add_subject.subject_code_placeholder')"
                                       required>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.subject_title')</label>
                                <input type="text" name="subject-title" class="form-control"
                                       placeholder="@lang('subject_management/add_subject.subject_title_placeholder')"
                                       required>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.coefficient')</label>
                                <input type="text" name="coefficient" class="form-control input-mask" data-mask="00"
                                       placeholder="eg: 05" required>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="col-sm-4 col-md-3">
                                    <label>@lang('subject_management/add_subject.active_state')</label>
                                    <br>
                                    <div class="form-group">
                                        <div class="toggle-switch">
                                            <input type="checkbox" id="statebtn" name="state" value="false"
                                                   class="toggle-switch__checkbox" data-toggle="toggle">
                                            <i class="toggle-switch__helper"></i>
                                        </div>
                                    </div>
                                </div>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.select_class')</label>
                                <select class="select2" name="class-code">
                                    {!! \App\AcademicLevel::getClassList() !!}
                                </select>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.series_code')</label>
                                <select class="select2" multiple data-placeholder="Select one or more choices"
                                        name="series-code[]">
                                    {!! \App\Series::getSeriesList() !!}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.subject_weight')</label>
                                <input type="text" name="subject-weight" class="form-control input-mask" data-mask="00"
                                       placeholder="eg: 20" required>
                                <i class="form-group__bar"></i>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('subject_management/add_subject.select_program')</label>
                                <select class="select2" name="program" required>
                                    {!! \App\Program::getProgramsList() !!}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="position:relative;left: 20%;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="academic-year"
                                       style="color: black;">@lang('subject_management/add_subject.academic_year')</label>
                                <select class="select2" name="academic-year" required>
                                    {!! \App\AcademicLevel::getAcademicYearList() !!}
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="mt-5 text-center">
                        <button type="submit" class="btn c-ewangclarks" style="width: 30%;"><i
                                class="zmdi zmdi-account-add"></i> @lang('subject_management/add_subject.add_subject_header')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.add_subject')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        var switchStatus = false;
        $('input[type="checkbox"]').prop('checked', false);
        $('#statebtn').val(switchStatus);

        $("#statebtn").on('change', function () {
            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                $('#statebtn').val(switchStatus);
            } else {
                switchStatus = $(this).is(':checked');
                $('#statebtn').val(switchStatus);
            }
            console.log(switchStatus);

        });

    </script>
@endsection
