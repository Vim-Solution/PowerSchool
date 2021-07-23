@extends('layouts.app')

@section('title')
    @lang('announcement/announcement.annoucement_header')
@endsection

@section('content')
    <br>
    <div class="row">
        <div class="col-lg-8 col-md-7">
            <div class="card">
                {!! $current_announcement !!}
                <br>
                <br><br>
            </div>
        </div>

        <div class="col-lg-4 col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('announcement/announcement.announcement_caption')</h4>
                    <h6 class="card-subtitle">@lang('announcement/announcement.announcement_caption_info')</h6>
                </div>

                <div class="listview listview--hover">
                    {!! $active_announcements !!}
                    <div class="m-4"></div>
                </div>
                <br><br>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.academic_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.announcement')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

    </script>
@endsection
