@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('student_management/student_portal_access.portal_access_header')
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
                <img src="{{asset(trans('img/img.student'))}}" alt="" height="200px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;">
                    <b>{{ trans('student_management/student_portal_access.portal_access_title') }}</b></h3><br><br>

                <ul class="icon-list">
                    <li>
                        <form method="post" enctype="multipart/form-data"
                              action="{{ trans('settings/routes.manage_student_portal_access')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="academic-year"
                                               style="color: black;">@lang('student_management/student_portal_access.select_academic_year')</label>
                                        <select class="select2 form-control" name="academic-year" id="academic-year">
                                            {!! \App\Setting::getAcademicYearsList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-5"><br>
                                    <button type="submit" class="btn c-ewangclarks"
                                            style="width: 100%;">
                                        <h6 class="text-white"><i
                                                class="zmdi zmdi-male-female"></i>@lang('student_management/student_portal_access.get_portal_access')
                                        </h6>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            {!!  $student_list  !!}
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
        var catName = '#' + "<?php echo trans('authorization/category.student_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_student_portal_access')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection
