@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
@endsection

@section('title')
    @lang('access_manager/manage_role.manage_role_header')
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
    <div class="card profile">
        <div class="profile__img">
            <img src="{{asset(trans('img/img.default_profile'))}}" alt="">

            <a href="#" class="zmdi zmdi-camera profile__img__edit"></a>
        </div>

        <div class="profile__info">
            <h3 style="color: #0D0A0A;">
                <b>{{ trans('access_manager/manage_role.edit_role_header',['role' => $role->role_name]) }}</b></h3><br>

            <form method="post" enctype="multipart/form-data"
                  action="{{ trans('settings/routes.manage_role') . trans('settings/routes.edit') . '/' . \App\Encrypter::encrypt($role->role_id)}}">
                @csrf
                <ul class="icon-list">
                    <li>
                        <div class="form-group">
                            <label for="role-name"
                                   style="color: black;">@lang('access_manager/manage_role.role_name_placeholder')</label>
                            <input type="text" class="form-control" name="role-name" value="{{ $role->role_name }}"
                                   required>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <label for="description"
                                   style="color: black;">@lang('access_manager/manage_role.role_description')</label>
                            <input type="text" class="form-control" name="role-description" id="description"
                                   value="{{  $role->description}}"
                                   required>
                        </div>
                    </li>
                    <li>
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 100%;">
                            <h6 class="text-white"><i
                                        class="zmdi zmdi-edit"></i>@lang('access_manager/manage_role.edit_role')</h6>
                        </button>
                        <br><br><br>
                    </li>
                </ul>
            </form>
            <a href="{{ trans('settings/routes.manage_role') }}" class="btn btn-primary text-white"
               style="position: absolute;right: 5%; ">
                <i class="zmdi zmdi-arrow-back"></i>@lang('actions/action.change_role')
            </a><br><br>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/trumbowyg/trumbowyg.min.js') }}"></script>
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.administration') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_role')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection