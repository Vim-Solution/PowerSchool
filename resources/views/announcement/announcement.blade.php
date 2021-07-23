@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
@endsection

@section('title')
    @lang('announcement/announcement.announcement_header')
@endsection

@section('content')
    <br><br>
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
    <div class="card team__item"><br>
        <div class="card-body">
            <div class="card-header c-ewangclarks">
                <h1 class="card-title text-white">@lang('announcement/announcement.announcement_t')</h1>
            </div><br>
            <a href="{{  trans('settings/routes.announcement_list')}}"
               class="btn c-ewangclarks" style="width: 28%;position: relative;left: 33%;">
                <h6 class="text-white"><i
                        class="zmdi zmdi-view-list"></i>@lang('actions/action.view_announcements')
                </h6>
            </a>
            <br><br>
            <form method="post"
                  action="{{ trans('settings/routes.announcement')}}"
                  enctype="multipart/form-data">
                @csrf
                <div class="container" style="width: 80%;position:relative;left: 20%;">
                    <div class="col-sm-7 text-left">
                        <div class="form-group">
                            <label style="color: black;" >{{ trans('announcement/announcement.subject') }}</label>
                            <input id="subject" type="text" class="form-control" name="subject" required>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-7 text-left" >
                        <div class="form-group">
                            <label style="color: black;" for="body">{{ trans('announcement/announcement.body') }}</label>
                            <textarea class="form-control fg-line" name="body" id="body"></textarea>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-7 text-left">
                        <div class="form-group">
                            <label for="staff"
                                   style="color: black;">@lang('announcement/announcement.select_staff')</label>
                            <select class="select2" name="staff[]" id="staff" multiple>
                                {!! \App\User::getStaffList() !!}
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn c-ewangclarks"
                        style="width: 35%;padding-top: 6px;padding-bottom: 5px; position: relative;left: 1%;">
                    <h6 class="text-white"><i class="zmdi zmdi-notifications-add"></i>@lang('actions/action.announce')
                    </h6>
                </button>
                <br><br><br>
            </form>
            <br>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.academic_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.announcement')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

    </script>
@endsection

