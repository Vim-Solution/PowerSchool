@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('student_management/batch_student_upload.batch_student_upload_header')
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
                <img src="{{asset(trans('img/img.student'))}}" alt="" height="250px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 10%;">
                    <b>{{ trans('student_management/batch_student_upload.batch_student_upload_title') }}</b></h3>

                <button class="btn btn-success" style="width: 45%;position: relative;left: 55%;bottom: 100%;"
                        onclick="downloadTable('sample-format')"><i
                            class="zmdi zmdi-download"></i> @lang('student_management/batch_student_upload.download_sample')
                </button>
                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.batch_student_upload')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('student_management/batch_student_upload.select_class')</label>
                                        <select class="select2" name="class-code">
                                            {!! \App\AcademicLevel::getClassList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('student_management/batch_student_upload.select_program')</label>
                                        <select class="select2" name="program-code">
                                            {!! \App\Program::getProgramsList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <p style="color: black;">@lang('student_management/batch_student_upload.select_file')<br>
                                            <label for="file-name" class="text-center"
                                                   style="padding-top:5px;padding-bottom: 0px;padding-left:15px;margin: 0px;color: red;font-size: 10px;"
                                                   id="file-text">@lang('student_management/batch_student_upload.select_file_text')</label>
                                        <hr>
                                        <input type="file" class="vims-file-input" name="student-csv-file" value=""
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
            {!! $student_list !!}
        </div>

        <table id="sample-format" class="hidden">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('student_management/batch_student_upload.full_name')</th>
                <th>@lang('student_management/batch_student_upload.date_of_birth')</th>
                <th>@lang('student_management/batch_student_upload.place_of_birth')</th>
                <th>@lang('student_management/batch_student_upload.region_of_origin')</th>
                <th>@lang('student_management/batch_student_upload.father_address')</th>
                <th>@lang('student_management/batch_student_upload.mother_address')</th>
                <th>@lang('student_management/batch_student_upload.tutor_name')</th>
                <th>@lang('student_management/batch_student_upload.tutor_address')</th>
                <th>@lang('student_management/batch_student_upload.admission_date')</th>
                <th>@lang('student_management/batch_student_upload.series')</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s1')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s2')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s3')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s4')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s5')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s6')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s7')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.s8')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.a1')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.a2')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.a3')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.a4')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.a5')</td>
            </tr>
            <tr>
                <td>Ewang clarkson</td>
                <td>06 september 1995</td>
                <td>bamenda</td>
                <td>center</td>
                <td>673656304</td>
                <td>673656304</td>
                <td>fanyi</td>
                <td>673656304</td>
                <td>06 september 2018</td>
                <td>@lang('student_management/batch_student_upload.a6')</td>
            </tr>
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
        var catName = '#' + "<?php echo trans('authorization/category.student_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.batch_student_upload')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('#file-name').change(function () {
            var fileText = "<?php echo trans('student_management/batch_student_upload.file_selected') ?>";
            $('#file-text').html(fileText.toString());
            $('#file-text').css({"color": "blue"});
        });
    </script>
@endsection
