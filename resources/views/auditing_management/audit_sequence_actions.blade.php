@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
@endsection

@section('title')
    @lang('auditing_management/audit_sequence_actions.audit_sequence_action_header')
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
        {!! session('status') !!}
    @endif
    <div class="card">
        <div class="profile">
            <div class="profile__img">
                <img src="{{asset(trans('img/img.book_logo_p'))}}" alt="" height="300px" width="400px">
            </div>

            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 20%;">
                    <b>{{ trans('auditing_management/audit_sequence_actions.audit_sequence_action_title') }}</b></h3><br><br>

                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.audit_sequence_actions')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="action"
                                               style="color: black;">@lang('auditing_management/audit_sequence_actions.select_action')</label>
                                        <select class="select2" name="action">
                                            {!! App\Setting::getActionListThree() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="academic-year"
                                               style="color: black;">@lang('auditing_management/audit_sequence_actions.select_academic_year')</label>
                                        <select class="select2" name="academic-year">
                                            {!! App\Setting::getAcademicYearsList() !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <button type="submit" class="btn c-ewangclarks"
                                    style="width: 50%;position: relative;left: 10%">
                                <h6 class="text-white"><i
                                        class="zmdi zmdi-local-activity"></i>@lang('actions/action.get_activities')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="card-body">
            {!! $activity_list !!}
        </div>

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
                var catName = '#' + "<?php echo trans('authorization/category.auditing_management') ?>";
                var privName = '#' + "<?php echo trans('authorization/privilege.audit_sequence_actions')?>";
                catId = catName.replace(/ /g, "_");
                privId = privName.replace(/ /g, "_");

                $(privId).addClass('navigation__active');
                $(catId).addClass('navigation__sub--active navigation_sub--toggled');
            </script>
@endsection
