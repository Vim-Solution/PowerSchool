@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/trumbowyg/ui/trumbowyg.min.css') }}">
@endsection

@section('title')
    @lang('settings/setting.matricule_setting_header')
@endsection
@section('content')
    <br>
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
    <div class="card">
    <div class="profile">
        <div class="profile__img">
            <img src="{{asset(trans('img/img.school_p'))}}" alt="" height="300px" width="400px">
        </div>

        <div class="profile__info" style="width: 100%;">
            <h3 style="color: #0D0A0A;position: relative;left: 20%;">
                <b>{{ trans('settings/setting.matricule_setting_header') }}</b></h3><br><br>

            <form method="post" enctype="multipart/form-data"
                  action="{{ trans('settings/routes.matricule_setting')}}">
                @csrf
                <ul class="icon-list">
                    <li>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="role-name"
                                           style="color: black;">@lang('settings/setting.matricule_initial')</label>
                                    <input type="text" class="form-control" name="matricule-initial"
                                           placeholder="LBA ..."
                                           required>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="program"
                                           style="color: black;">@lang('settings/setting.select_program')</label>
                                    <select class="select2" name="program_code">
                                        {!! \App\Program::getProgramsList() !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <button type="submit" class="btn c-ewangclarks"
                                style="width: 50%;position: relative;left: 10%">
                            <h6 class="text-white"><i
                                    class="zmdi zmdi-arrow-forward"></i>@lang('actions/action.submit')</h6>
                        </button>
                        <br><br><br>
                    </li>
                </ul>
            </form>
        </div>
    </div>
        <div class="card-body">
            {!! $matricule_setting_list !!}
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
        var privName = '#' + "<?php echo trans('authorization/privilege.matricule_setting')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');
    </script>
@endsection
