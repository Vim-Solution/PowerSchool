@extends('layouts.app')

@section('title')
    @lang('subject_management/edit_subject.edit_subject_header')
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


    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>

        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="post"
                  action="{{ trans('settings/routes.search_subject') }}">
                @csrf()
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">Enter keyword</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input type="text" name="q" class="form-control"
                                   placeholder="enter any keyword of the subject infos">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;position: relative;top: ;">@lang('subject_management/edit_subject.search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

        var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.edit_subject')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

    </script>
@endsection
