@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
@endsection

@section('title')
    @lang('academic_setting/manage_class.add_class_header')
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
    <div class="card">
        <div class="profile">
            <div class="profile__img">
                <img src="{{asset(trans('img/img.series_ladder_p'))}}" alt="" height="300px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 7%;">
                    <b>{{ trans('academic_setting/manage_class.manage_class_t') }}</b></h3><br>
                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.manage_class')}}">
                    @csrf
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="class-name"
                                               style="color: black;">@lang('academic_setting/manage_class.enter_class_name')</label>
                                        <input type="text" class="form-control" name="class-name" id="class-name"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="class-code"
                                                   style="color: black;">@lang('academic_setting/manage_class.enter_class_code')</label>
                                            <input type="text" class="form-control" name="class-code" id="class-code"
                                                   placeholder="e.g fm" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="program-code"
                                               style="color: black;">@lang('academic_setting/manage_class.select_program')</label>
                                        <select class="select2" name="program-code" id="program-code">
                                            {!! \App\Program::getProgramsList() !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="promotion-average"
                                                   style="color: black;">@lang('academic_setting/manage_class.class_promotion_average')</label>
                                            <input type="number" class="form-control" step="0.001"
                                                   name="promotion-average" id="promotion-average" placeholder="e.g 10"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="p-class-code"
                                               style="color: black;">@lang('academic_setting/manage_class.select_next_class')</label>
                                        <select class="select2" name="p-class-code" id="p-class-code">
                                            {!! App\AcademicLevel::getPromotionClassList() !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <button type="submit" class="btn c-ewangclarks"
                                    style="width: 50%;position: relative;left: 16%;">
                                <h6 class="text-white"><i
                                        class="zmdi zmdi-plus-circle"></i>@lang('actions/action.create_class')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>

        <div class="card-body">
            {!! $class_list !!}
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('template/vendors/trumbowyg/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.academic_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_class')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection
