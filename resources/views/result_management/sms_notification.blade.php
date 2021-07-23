@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('result_management/sms_notification.sms_notification_header')
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
                <img src="{{asset(trans('img/img.sms'))}}" alt="" height="200px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;">
                    <b>{{ trans('result_management/sms_notification.sms_notification_title') }}</b></h3><br>
                <ul class="icon-list">
                    <li>
                        <form method="post" enctype="multipart/form-data"
                              action="{{ trans('settings/routes.sms_notifications')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="class-code"
                                               style="color: black;">@lang('result_management/sms_notification.select_class')</label>
                                        <select class="select2 form-control" name="class-code" id="class-code">
                                            {!! App\AcademicLevel::getClassList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-5"><br>
                                    <button type="submit" class="btn c-ewangclarks"
                                            style="width: 100%;">
                                        <h6 class="text-white"><i
                                                class="zmdi zmdi-male-female"></i>@lang('actions/action.get_student_class_list')
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
            {!!  $sms_notification_list  !!}
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
        var privName = '#' + "<?php echo trans('authorization/privilege.sms_notifications')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection
