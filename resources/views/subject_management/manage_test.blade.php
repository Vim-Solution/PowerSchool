@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('subject_management/manage_test.manage_test_header')
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
                <img src="{{asset(trans('img/img.book_logo_p'))}}" alt="" height="250px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;">
                    <b>{{ trans('subject_management/manage_test.manage_test_t',['subject' => $subject->subject_title,'class' => \App\AcademicLevel::getClassNameByCode($subject->classes_class_code)]) }}</b>
                </h3><br>
                <form method="get"
                      action="{{ trans('settings/routes.manage_subject_test') . '/' . \App\Encrypter::encrypt($subject->subject_id) . trans('settings/routes.create')}}">
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="test-name"
                                               style="color: black;">@lang('subject_management/manage_test.enter_test_name')</label>
                                        <input type="text" class="form-control" name="test-name" id="test-name"
                                               placeholder="@lang('subject_management/manage_test.test_name_ph')"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="test-code"
                                               style="color: black;">@lang('subject_management/manage_test.enter_test_code')</label>
                                        <input type="text" class="form-control" name="test-code" id="test-code"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="test-weight"
                                               style="color: black;">@lang('subject_management/manage_test.enter_test_weight')</label>
                                        <input type="number" class="form-control" name="test-weight" id="test-weight"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <button type="submit" class="btn c-ewangclarks"
                                    style="width: 50%;position: relative;left: 16%;">
                                <h6 class="text-white"><i
                                        class="zmdi zmdi-view-agenda"></i>@lang('actions/action.submit')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="card-body">
            {!! $test_list !!}
        </div>
        @endsection

        @section('script')
            <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
            <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>
            <script src="{{ asset('js/jquery.battatech.excelexport.js') }}"></script>
            <script src="{{ asset('js/power-school.js') }}"></script>

            <script type="text/javascript">
                var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
                var privName = '#' + "<?php echo trans('authorization/privilege.manage_subject_test')?>";
                catId = catName.replace(/ /g, "_");
                privId = privName.replace(/ /g, "_");

                $(privId).addClass('navigation__active');
                $(catId).addClass('navigation__sub--active navigation_sub--toggled');
            </script>
@endsection
