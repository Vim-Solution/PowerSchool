@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
@endsection

@section('title')
    @lang('series_management/manage_student_series.manage_student_series_header_header')
@endsection

@section('content')
    <br>
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
        {!! session('status')!!}
    @endif

    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>

        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="get"
                  action="{{ trans('settings/routes.manage_student_series') . trans('settings/routes.get_student') }}">
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">@lang('series_management/manage_student_series.enter_matricule')</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group" style="position: relative;top: 14px;">
                            <input type="text" name="student-matricule" class="form-control" id="student-matricule" placeholder="Student matricule goes here.....">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;position: relative;top: ;"> <i class="zmdi zmdi-male-female"></i> @lang('actions/action.load_student')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {!! $student_information !!}

    <style type="text/css">
        #student-matricule{
            border-bottom:  2px;
            border-bottom-style: solid;
            border-bottom-color: #f9f9f9;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/trumbowyg/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.series_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_student_series')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();
    </script>
@endsection
