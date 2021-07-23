@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('account_management/account_setting.setting_header')
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
        <div class="card-body">
            <h1 class="card-title">@lang('student_management/batch_student_upload.batch_student_upload_header')</h1>
            <button class="btn btn-success" style="width: 30%;position: absolute;right: 2%;top: 10%;" onclick="downloadTable('sample-table')"><i class="zmdi zmdi-download"></i> @lang('student_management/batch_student_upload.download_sample')
            </button>

            <form enctype="multipart/form-data" method="post" action="{{trans('settings/routes.batch_student_upload')}}"
                  class="vims-file-upload" id="upload-file">
                @csrf()
                <input type="file" name="vims-file-upload[]" class="vims-file-input" id="vims-file-upload" multiple>
                <label for="vims-file-upload" class="btn vims-z-depth-4"> <i class="zmdi zmdi-cloud-upload"
                                                                               style="font-size: 22px"></i> @lang('student_management/batch_student_upload.drop_zone_text')
                </label>
                <br><br><br><br><br>
            </form>
            <br>

            <button class="btn btn-primary" style="width: 30%;position: relative;left: 35%;" id="vims-file-upload-submit">@lang('student_management/batch_student_upload.file_upload_text')
            </button>
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('js/jquery.battatech.excelexport.js') }}"></script>
    <script src="{{ asset('js/power-school.js') }}"></script>
    <script src="{{ asset('js/vims-file-upload.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.student_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.batch_student_upload')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

    </script>

@endsection