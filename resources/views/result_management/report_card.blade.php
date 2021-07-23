@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('result_management/report_card.report_card_header')
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
                    <b>{{ trans('result_management/report_card.report_card_title') }}</b></h3><br><br>

                <ul class="icon-list">
                    <li>
                        <form method="post" enctype="multipart/form-data"
                              action="{{ trans('settings/routes.print_report_card')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="matricule-no"
                                               style="color: black;">@lang('result_management/report_card.matricule_no')</label>
                                        <input type="text" class="form-control" name="matricule-no" value=""
                                               id="matricule-no"
                                               placeholder="i.e LBA..."
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="academic-year"
                                               style="color: black;">@lang('result_management/report_card.select_academic_year')</label>
                                        <select class="select2 form-control" name="academic-year" id="academic-year">
                                            {!! \App\Setting::getAcademicYearsList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-5"><br>
                                    <button type="submit" class="btn bg-green"
                                            style="width: 100%;">
                                        <h6 class="text-white"><i
                                                class="zmdi zmdi-print"></i>@lang('result_management/report_card.get_report_card')
                                        </h6>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li>
                        <form method="get" enctype="multipart/form-data"
                              action="{{ trans('settings/routes.print_class_report_card')}}">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program"
                                               style="color: black;">@lang('student_management/add_student.select_class')</label>
                                        <select class="select2" name="class-code">
                                            {!! \App\AcademicLevel::getClassList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="academic-year"
                                               style="color: black;">@lang('result_management/report_card.select_academic_year')</label>
                                        <select class="select2 form-control" name="academic-year" id="academic-year">
                                            {!! \App\Setting::getAcademicYearsList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-5"><br>
                                    <button type="submit" class="btn bg-blue"
                                            style="width: 100%;">
                                        <h6 class="text-white"><i
                                                class="zmdi zmdi-print"></i>@lang('result_management/report_card.get_report_cards')
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
            <div class="card">
                <div class="card-body">
                    @foreach($report_card as $report_c)
                        {!! $report_c !!} <br>
                    @endforeach
                </div>
            </div>
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
        var privName = '#' + "<?php echo trans('authorization/privilege.print_report_card')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection
