@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('subject_management/manage_test.manage_test_header')
@endsection

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            {!! $user_list !!}
        </div>
        @endsection

        @section('script')
            <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
            <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>
            <script src="{{ asset('js/power-school.js') }}"></script>

            <script type="text/javascript">
                var catName = '#' + "<?php echo trans('authorization/category.account_management') ?>";
                var privName = '#' + "<?php echo trans('authorization/privilege.user_list')?>";
                catId = catName.replace(/ /g, "_");
                privId = privName.replace(/ /g, "_");

                $(privId).addClass('navigation__active');
                $(catId).addClass('navigation__sub--active navigation_sub--toggled');
            </script>
@endsection
