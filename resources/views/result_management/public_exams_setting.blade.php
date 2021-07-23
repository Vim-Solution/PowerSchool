@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('result_management/manage_public_exams.manage_role_header')
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
                <img src="{{asset(trans('img/img.book_logo_p'))}}" alt="" height="300px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 18%;">
                    <b>{{ trans('result_management/manage_public_exams.manage_public_exams_title') }}</b></h3><br><br>

                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.public_exams')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="center-no"
                                               style="color: black;">@lang('result_management/manage_public_exams.center_no')</label>
                                        <input type="text" class="form-control" name="center-no" value="" id="center-no"
                                               placeholder="{{ trans('result_management/manage_public_exams.center_no_placeholder')}}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('result_management/manage_public_exams.select_program')</label>
                                        <select class="select2" name="program-code" id="program">
                                            {!! \App\Program::getProgramsList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <p style="color: black;">@lang('result_management/manage_public_exams.select_file')<br>
                                            <label for="file-name" class="text-center"
                                                   style="padding-top:5px;padding-bottom: 0px;padding-left:15px;margin: 0px;color: red;font-size: 10px;"
                                                   id="file-text">@lang('result_management/manage_public_exams.select_file_text')</label>
                                        <hr>
                                        <input type="file" class="vims-file-input" name="exam-pdf" value=""
                                               id="file-name"
                                               required>
                                        </p>
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
            {!! $exam_settings !!}
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

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.result_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.public_exams')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('#file-name').change(function () {
            var fileText = "<?php echo trans('result_management/manage_public_exams.file_selected') ?>";
            $('#file-text').html(fileText.toString());
            $('#file-text').css({"color": "blue"});
        });
    </script>
@endsection
