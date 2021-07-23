@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('title')
    @lang('access_manager/manage_access.manage_access_header')
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

    {!! $alert_info !!}

    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>

        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="get"
                  action="{{ trans('settings/routes.manage_privilege') . trans('settings/routes.authorizations') }}">
                @csrf()
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">{{ ucfirst(trans('access_manager/manage_access.select_role'))}}</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <select class="select2 select2-hidden-accessible form-control" name="role" id="role">
                                {!! \App\Role::getRolesList() !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;"> @lang('access_manager/manage_access.search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="messages" style="width: 90%;">
        <div class="ewangclarks__sidebar" style="width: 50%;">
            <div class="toolbar toolbar--inner c-ewangclarks">
                <div class="toolbar__label text-white">@lang('access_manager/manage_access.system_func')</div>

                <div class="actions toolbar__actions">
                    <i class="actions_item zmdi zmdi-nature text-white"></i>
                </div>
            </div>

            <div class="messages__search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search...">
                    <i class="form-group__bar"></i>
                </div>
            </div>

            <div class="listview listview--hover">
                <div class="scrollbar-inner" id="sm-func" style="position: relative;left: 1%;right: 1%;">
                    {!! $system_functionalities !!}
                </div>
                <br><br>
            </div>
        </div>
        <div class="ewangclarks__sidebar" style="width: 50%;left: 1%;">
            <div class="toolbar toolbar--inner c-ewangclarks">
                <div class="toolbar__label text-white">{{ $role_name }}</div>

                <div class="actions toolbar__actions">
                    <i class="actions_item zmdi zmdi-nature-people text-white"></i>
                </div>
            </div>

            <div class="messages__search">
                <div class="form-group">
                    {!! $role_users !!}
                    <i class="form-group__bar"></i>
                </div>
            </div>

            <div class="listview listview--hover">
                <div class="scrollbar-inner" id="us-func">
                    <hr style="padding: 1px 1px 1px 1px;margin: 0;">
                    {!! $role_functionalities !!}
                </div>
                <br><br>
            </div>
            <br><br>
        </div>
    </div><br>
    <button class="btn c-ewangclarks  zmdi zmdi-save"
            style="width: 40%;position: relative;left: 25%;"
            onclick="saveNewFunctionalities()">@lang('access_manager/manage_access.save')</button>

    <button class="btn  btn--action btn-info zmdi zmdi-help" onclick="inform()"></button>


@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/manage_access/manage_access.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.administration') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_privilege')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

        var roleId = parseInt("<?php echo $roleId ?>");
        if (roleId !== 0)
            $('#role').val(roleId);

        function inform() {
            var info = "<?php echo trans('access_manager/manage_access.swal_title') ?>";
            var body = "<?php echo trans('access_manager/manage_access.swal_body') ?>";
            var btnText = "<?php echo trans('access_manager/manage_access.swal_btn_text') ?>";
            swal({
                title: info,
                html: body,
                type: 'info',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: btnText,
            });
        }


        function saveNewFunctionalities() {

            var ids = [];
            $('#us-func').children().each(function () {
                if ($(this).attr('class') != undefined || $(this).attr('class') != null) {
                    ids.push($(this).attr('class'));
                }
            });
            if (roleId > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var title = "<?php echo trans('access_manager/manage_access.title') ?>";
                var body = "<?php echo trans('access_manager/manage_access.body') ?>";
                var url = "<?php  echo trans('settings/routes.manage_privilege') ?>"
                var alert_success = "<?php echo trans('access_manager/manage_access.success_alert') ?>";
                var alert_failure = "<?php echo trans('access_manager/manage_access.failure_alert') ?>";
                var msgs = "<?php echo trans('access_manager/manage_access.success_message') ?>";
                var msgf = "<?php echo trans('access_manager/manage_access.failure_message') ?>";

                swal({
                    title: title,
                    text: body,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                    allowEscapeKey: false,
                });
                var data = {"func-ids": ids, "role-id": roleId};
                swal.showLoading();
                $.ajax({
                    type: 'post',
                    url: url,
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function (result, txt, xhr) {
                        if (xhr.status === 200) {
                            $('.alert').removeClass('alert-success').addClass('alert-success').text(alert_success);
                            if (result.status == 1) {
                                swal({
                                    title: msgs,
                                    type: 'success',
                                    showCancelButton: false,
                                    buttonsStyling: false,
                                    confirmButtonClass: 'btn btn-danger',
                                    confirmButtonText: 'Okay, Thanks!',
                                    cancelButtonClass: 'btn btn-secondary'
                                }).then(function () {
                                    window.location.reload();
                                });
                            } else {
                                swal({
                                    title: msgs,
                                    type: 'success',
                                    showCancelButton: false,
                                    buttonsStyling: false,
                                    confirmButtonClass: 'btn btn-danger',
                                    confirmButtonText: 'Okay, Thanks!',
                                    cancelButtonClass: 'btn btn-secondary'
                                });
                            }
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
                        }
                    }
                });
            }
        }


    </script>
@endsection
