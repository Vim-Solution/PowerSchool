@extends('layouts.app')

@section('title')
    @lang('announcement/announcement.announcement_header')
@endsection

@section('content')
    @if(session('status'))
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show text-center">
                {!!  session('status') !!}
            </div>
        </div>
    @endif
    <br>
    <div class="card issue-tracker">
            <h3 class="text-center p-5">{{ trans('announcement/announcement.announcement_h') }}</h3><hr>

        <div class="listview listview--bordered">
            {!! $announcements !!}
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
