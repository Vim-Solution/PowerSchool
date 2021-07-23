@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('title')
    @lang('subject_management/assign_subject.assign_subject_header')
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
                <img src="{{asset(trans('img/img.book_logo_p'))}}" alt="" height="250px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 10%;">
                    <b>{{ trans('subject_management/assign_subject.assign_subject_title') }}</b></h3><br>
                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.manage_teacher_subject')}}" id="assign-subject">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="class"
                                               style="color: black;">@lang('subject_management/assign_subject.select_class')</label>
                                        <select class="select2" name="class-code" id="class-code">
                                            {!! \App\AcademicLevel::getEncodedClassList() !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="teacher"
                                               style="color: black;">@lang('subject_management/assign_subject.select_teacher')</label>
                                        <select class="select2" name="teacher-code" id="teacher-code">
                                            {!! \App\User::getEncodedUserList() !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div id="alert-notifier">

        </div>
        <div class="card-body">
            <div class="messages " style="width: 90%;">
                <div class="ewangclarks__sidebar" style="width: 50%;">
                    <div class="toolbar toolbar--inner c-ewangclarks">
                        <div class="toolbar__label text-white"
                             id="class-name">@lang('subject_management/assign_subject.class_panel_title')</div>

                    </div>

                    <div class="listview listview--hover">
                        <div class="scrollbar-inner" id="sm-func" style="position: relative;left: 1%;right: 1%;">

                        </div>
                        <br><br>
                    </div>
                </div>
                <div class="ewangclarks__sidebar" style="width: 50%;left: 1%;">
                    <div class="toolbar toolbar--inner c-ewangclarks">
                        <div class="toolbar__label text-white"
                             id="teacher-name">@lang('subject_management/assign_subject.teacher_panel_title')</div>
                    </div>

                    <div class="listview listview--hover" id="us-func">
                        <br><br>
                    </div>
                    <br><br>
                </div>
            </div>
            <br>
            <button class="btn c-ewangclarks  zmdi zmdi-save" onClick="submitForm()"
                    style="width: 40%;position: relative;left: 25%;">@lang('access_manager/manage_access.save')</button>
        </div>
        <br><br><br>

    </div>
@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/subject_management/assign_subject.js') }}"></script>
    <script src="{{ asset('js/manage_access/manage_access.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_teacher_subject')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        var encodedZero =  "<?php echo \App\Encrypter::encrypt(0) ?>"

        var url = "<?php echo trans('settings/routes.load_subjects') ?>"

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
        $(document).ready(function () {
            $('#class-code').change(function () {
                if ($('#teacher-code').val() == null) {
                    route = url + '/' + encodedZero + '/' + $(this).val() + '/1';
                } else {
                    route = url + '/' + $('#teacher-code').val() + '/' + $(this).val() + '/1';
                }
                $.ajax({
                    type: 'get',
                    url: route,
                    success: function (data, txt, xhr) {
                        if (xhr.status === 200) {
                            $('#sm-func').html(data.class_subject_list)
                            $('#class-name').html(data.class_name)
                            $('#us-func').html(data.teacher_subject_list)
                        }
                    }
                });
            })

            $('#teacher-code').change(function () {
                if ($('#class-code').val() == null) {
                    route = url + '/' + $(this).val() + '/' + encodedZero + '/0';
                } else {
                    route = url + '/' + $(this).val() + '/' + $('#class-code').val() + '/0';

                }
                $.ajax({
                    type: 'get',
                    url: route,
                    success: function (data, txt, xhr) {
                        if (xhr.status === 200) {
                            $('#us-func').html(data.teacher_subject_list)
                            $('#teacher-name').html(data.teacher_name)
                            $('#sm-func').html(data.class_subject_list)
                        }
                    }
                });
            })
        });

        function submitForm() {
            var ids = [];
            var sentinel = 0;
            $('#us-func').children().each(function () {
                if ($(this).attr('class') != undefined || $(this).attr('class') != null) {
                    ids.push($(this).attr('class'));
                    sentinel++;
                }
            });

            if (sentinel > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var title = "<?php echo trans('access_manager/manage_access.title') ?>";
                var body = "<?php echo trans('access_manager/manage_access.body') ?>";
                var link = "<?php  echo trans('settings/routes.load_subjects') . '/-1/-1/-1' ?>";
                var alert_success = "<?php echo trans('access_manager/manage_access.success_alert') ?>";
                var alert_failure = "<?php echo trans('access_manager/manage_access.failure_alert') ?>";
                var msgs = "<?php echo trans('access_manager/manage_access.s_success_message') ?>";
                var msgf = "<?php echo trans('access_manager/manage_access.failure_message') ?>";
                 success_alert = '<?php echo \App\Setting::getAlertSuccess(trans('access_manager/manage_access.s_success_message')) ?>';
                swal({
                    title: title,
                    text: body,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                    allowEscapeKey: false,
                });
                var data = {"ids": ids};
                swal.showLoading();
                $.ajax({
                    type: 'post',
                    url: link,
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function (result, txt, xhr) {
                        if (xhr.status === 200) {
                            $('.alert').removeClass('alert-success').addClass('alert-success').text(alert_success);
                            swal({
                                title: msgs,
                                type: 'success',
                                showCancelButton: false,
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-success',
                                confirmButtonText: 'Okay, Thanks!',
                                cancelButtonClass: 'btn btn-secondary'
                            });
                            $('#alert-notifier').html(success_alert);
                        } else {
                            $('.alert').removeClass('alert-success').addClass('alert-warning').text(alert_failure);
                            swal({
                                title: msgf,
                                type: 'error',
                                showCancelButton: false,
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-danger',
                                confirmButtonText: 'Okay, Thanks!',
                                cancelButtonClass: 'btn btn-secondary'
                            });
                            $('#alert-notifier').html(failure_alert);
                        }
                    }
                });
            }
        }
    </script>
@endsection
