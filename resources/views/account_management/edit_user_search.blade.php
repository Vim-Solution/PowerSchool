@extends('layouts.app')

@section('title')
    @lang('account_management/edit_user.edit_user_header')
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

    {!! $success_alert !!}

    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>

        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="post"
                  action="{{ trans('settings/routes.edit_user') }}">
                @csrf()
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">{{ ucfirst(trans('account_management/edit_user.enter_email'))}}</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control"
                                   placeholder="email goes here.....">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;position: relative;top: ;">@lang('account_management/edit_user.search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="">
        {!! $profile !!}
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.account_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.edit_user')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();
    </script>
@endsection