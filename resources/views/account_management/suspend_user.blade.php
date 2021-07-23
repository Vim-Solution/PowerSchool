@extends('layouts.app')

@section('title')
    @lang('account_management/suspend_user.suspend_user_header')
@endsection

@section('content')
    @if(session('status'))
        {!! session('status') !!}
    @endif
    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>

        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="post"
                  action="{{ trans('settings/routes.suspend_user') }}">
                @csrf()
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="background-color:@lang('settings/theme.red');position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">{{ ucfirst(trans('account_management/edit_user.enter_email'))}}</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="email goes here.....">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;background-color:@lang('settings/theme.red');position: relative;top: ;"> @lang('account_management/edit_user.search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="contacts row">
        {!! $accounts !!}
    </div>
    <br>
@endsection
@section('script')
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.account_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.suspend_user')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

    </script>
@endsection