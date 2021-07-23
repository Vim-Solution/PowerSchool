@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('subject_management/get_class_list.get_class_list_header')
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
                <h4 style="color: #0D0A0A;">
                    <b>{{ trans('subject_management/get_class_list.get_class_list_title') }}</b></h4><br>

                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.get_class_list')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="class-code"
                                               style="color: black;">@lang('subject_management/get_class_list.select_class')</label>
                                        <select class="select2" name="class-code" id="class-code"required>
                                            {!! \App\AcademicLevel::getClassList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="academic-year"
                                               style="color: black;">@lang('subject_management/get_class_list.select_academic_year')</label>
                                        <select class="select2" name="academic-year" required>
                                            {!! \App\AcademicLevel::getAcademicYearList() !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <button type="submit" class="btn c-ewangclarks"
                                    style="width: 50%;position: relative;left: 16%;">
                                <h6 class="text-white"><i
                                            class="zmdi zmdi-cloud-download"></i>  @lang('actions/action.get_class_list')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="card-body">
            {!! $class_list !!}
        </div>

        <table id="sample-format" class="hidden">
            <thead class="thead-default c-ewangclarks text-white">
            <tr>
                <th>@lang('subject_management/get_class_list.student_name')</th>
                <th>@lang('subject_management/get_class_list.mat_number')</th>
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
        var catName = '#' + "<?php echo trans('authorization/category.student_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.get_class_list')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

    </script>
@endsection
