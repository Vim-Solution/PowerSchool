@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('subject_management/series_data_upload.series_data_upload_header')
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
                <h3>
                    <b>{{ trans('subject_management/series_data_upload.series_data_upload_title') }}</b></h3><br>

                <button class="btn btn-success" style="width: 45%;position: relative;left: 55%;bottom: 100%;" onclick="downloadTable('sample-format')"><i class="zmdi zmdi-download"></i> @lang('subject_management/series_data_upload.download_sample')
                </button>
                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.series_data_upload')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('subject_management/series_data_upload.select_class')</label>
                                        <select class="select2" name="class-code" required>
                                            {!! \App\AcademicLevel::getClassListSecondCycle() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('subject_management/series_data_upload.program')</label>
                                        <select class="select2" name="program-code" id="program" required readonly>
                                            {!! \App\Program::getProgramsListSecondCyle() !!}
                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <p style="color: black;">@lang('subject_management/series_data_upload.select_file')<br>
                                            <label for="file-name" class="text-center"
                                                   style="padding-top:5px;padding-bottom: 0px;padding-left:15px;margin: 0px;color: red;font-size: 10px;"
                                                   id="file-text">@lang('subject_management/series_data_upload.select_file_text')</label>
                                        <hr>
                                        <input type="file" class="vims-file-input" name="student_series-csv-file" value=""
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
                                            class="zmdi zmdi-cloud-upload"></i>@lang('actions/action.submit')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="card-body">
            {!! $student_series_list !!}
        </div>

        <table id="sample-format" class="hidden">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('subject_management/series_data_upload.student_matricule')</th>
                <th>@lang('subject_management/series_data_upload.student_name')</th>
                <th>@lang('subject_management/series_data_upload.series_code')</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/jquery.battatech.excelexport.js') }}"></script>
    <script src="{{ asset('js/power-school.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.series_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.series_data_upload')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');


        $('#program').val("<?php echo "al" ?>");

        $('#file-name').change(function () {
            var fileText = "<?php echo trans('subject_management/series_data_upload.file_selected') ?>";
            $('#file-text').html(fileText.toString());
            $('#file-text').css({"color": "blue"});
        });
    </script>
@endsection
