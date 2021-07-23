@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('student_portal/student_info.student_info_header')
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

    {!! $success_alert !!}
    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>

        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="post"
                  action="{{ trans('settings/routes.student_info') }}">
                @csrf()
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">@lang('student_portal/student_info.full_name_text')</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group" style="position: relative;top: 14px;">
                            <input type="text" name="student-name" class="form-control" id="student-name"
                                   placeholder="Student full name goes here.....">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;position: relative;top: ;"><i
                                class="zmdi zmdi-arrow-forward"></i> @lang('student_portal/student_info.btn_text')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {!! $student_details !!}


    <style type="text/css">
        #student-name {
            border-bottom: 2px;
            border-bottom-style: solid;
            border-bottom-color: #f9f9f9;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('js/notify.js')}}"></script>
    <script type="text/javascript">
        $('#student-info').addClass('navigation__active');
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();
    </script>
@endsection
