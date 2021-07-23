@extends('layouts.app')

@section('title')
    @lang('student_management/generate_id.generate_id_header')
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
    <div class="toolbar">
        <nav class="toolbar__nav">
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>
        </div>
        <div class="toolbar__search">
            <form enctype="multipart/form-data" method="post"
                  action="{{ trans('settings/routes.id_card') }}">
                @csrf()
                <div class="row" style="padding-top: 1%;">
                    <div class="col-sm-3 c-ewangclarks"
                         style="position: relative;top: 10%;height: 50px;">
                        <label class="text-white"
                               style="position: relative;left:22%;top: 30%;">@lang('student_management/generate_id.keyword')</label>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input type="text" name="q" class="form-control"
                                   placeholder="enter any keyword of the student infos">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 80%;position: relative;top: ;">@lang('student_management/generate_id.search')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {!! $student_id_card !!}


@endsection

@section('script')
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.student_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.id_card')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();
    </script>
@endsection
