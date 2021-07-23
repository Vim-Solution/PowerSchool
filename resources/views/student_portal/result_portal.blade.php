@extends('layouts.app')


@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection


@section('title')
    @lang('student_portal/result_portal.result_portal_header')
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
    <br>
    <div class="card animation-demo">
        <div class="profile">
            <div class="profile__img">
                <img src="{{asset(trans('img/img.book_logo_p'))}}" alt="" height="300px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 18%;">
                    <b>{{ trans('student_portal/result_portal.result_portal_title') }}</b></h3><br><br>

                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.result_portal')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="result-type"
                                               style="color: black;">@lang('student_portal/result_portal.select_result_type')</label>
                                        <select class="select2 form-control" name="result-type" id="result-type">
                                            {!! \App\Setting::getResultTypeList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="academic-year"
                                               style="color: black;">@lang('student_portal/result_portal.select_academic_year')</label>
                                        <select class="select2 form-control" name="academic-year" id="academic-year">
                                            {!! \App\Setting::getAcademicYearsList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="matricule-no"
                                               style="color: black;">@lang('student_portal/result_portal.matricule_no')</label>
                                        <input type="text" class="form-control" name="matricule-no" id="matricule-no"
                                               placeholder="@lang('student_portal/result_portal.matricule_no_placeholder')"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="secret-code"
                                               style="color: black;">@lang('student_portal/result_portal.secret_code')</label>
                                        <input type="password" class="form-control" name="secret-code" required>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <button type="submit" class="btn c-ewangclarks"
                                    style="width: 50%;position: relative;left: 16%;">
                                <h6 class="text-white"><i
                                        class="zmdi zmdi-arrow-forward"></i>@lang('student_portal/result_portal.get_result_text')
                                </h6>
                            </button>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="card-body">
            {!! $result_details !!}
            <br><br>
        </div>
    </div>
    <div class="toolbar">
        <div class="toolbar__label" style="width: 80%;">
            <div class="row">
                <div class="c-ewangclarks" style="padding-left: 30px;padding-right: 20px;padding-bottom:10px;">
                    <br>
                    <label class="text-white">{{ ucfirst(trans('general.set_language'))}}</label>
                </div>
                <div style="width: 30%;padding-left: 15px;z-index: 11;">
                    <p></p>
                    <form action="{{ trans('settings/routes.s_change_locale') }}" method="get" id="change-lo"
                          enctype="multipart/form-data">
                        <select class="select2" data-placeholder="{{ trans('general.select_language') }}"
                                name="change-locale" id="change-locale">
                            <option value="none" selected
                                    disabled>{{ ucfirst(trans('general.select_language')) }}</option>
                            <option value="en">{{ ucfirst(trans('general.en'))}}</option>
                            <option value="fr">{{ ucfirst(trans('general.fr'))}}</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>
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
    <script src="{{ asset('template/vendors/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('js/notify.js')}}"></script>

    <script type="text/javascript">
        $('#result-portal').addClass('navigation__active');

        $('#change-locale').change(function () {
            $('#change-lo').submit();
        });
    </script>
    @if((date('d m',strtotime($date)) == date('d m')) && ($date != ''))
        <script type="text/javascript">
            $(document).ready(function() {
                var message = "<?php echo(trans('student_portal/student_info.happy_birthday', ['name' => $full_name])) ?>"
                notify('bottom', 'left', 'fa fa-comment', 'success', 'animated fadeInLeft', 'animated fadeOutLeft',message);
            });
        </script>
    @endif

@endsection
