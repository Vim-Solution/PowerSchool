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
    @php
        $academicYear = App\Setting::getAcademicYear();
        $sequence = App\Setting::getSequence();
        $teacherId = \Illuminate\Support\Facades\Auth::user()->user_id;
    @endphp
    <div class="card">
        <div class="profile">
            <div class="profile__img">
                <img src="{{asset(trans('img/img.book_logo_p'))}}" alt="" height="250px;" width="400px">
            </div>
            <div class="profile__info" style="width: 100%;">
                <h3 style="color: #0D0A0A;position: relative;left: 18%;">
                    <b>{{ trans('subject_management/manage_test.result_list_title') }}</b></h3><br>
                <form method="post" enctype="multipart/form-data"
                      action="{{ trans('settings/routes.result_list')}}">
                    @csrf()
                    <ul class="icon-list">
                        <li>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="subject-list"
                                               style="color: black;">@lang('subject_management/manage_test.select_subject')</label>
                                        <select class="select2" name="subject-id" id="subject-list">
                                            {!! \App\Subject::getSubjectListByTeacherId($teacherId,$academicYear) !!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="sequence-list"
                                               style="color: black;">@lang('subject_management/manage_test.select_sequence')</label>
                                        <select class="select2" name="sequence-id" id="sequence-list">
                                            {!! \App\Sequence::getSequenceListByTeacherId($teacherId,$academicYear) !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <button type="submit" class="btn c-ewangclarks"
                                    style="width: 50%;position: relative;left: 16%;">
                                <h6 class="text-white"><i
                                        class="zmdi zmdi-view-agenda"></i>@lang('actions/action.get_result_list')</h6>
                            </button>
                            <br><br><br>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="card-body">
            {!! $result_list !!}
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
                var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
                var privName = '#' + "<?php echo trans('authorization/privilege.manage_subject_test')?>";
                catId = catName.replace(/ /g, "_");
                privId = privName.replace(/ /g, "_");

                $(privId).addClass('navigation__active');
                $(catId).addClass('navigation__sub--active navigation_sub--toggled');
            </script>
@endsection
