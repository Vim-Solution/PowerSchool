@extends('layouts.app')

@section('title')
    @lang('result_management/publish_result.publish_result_header')
@endsection
@section('content')
    @if(session('status'))
        {!! session('status') !!}
    @endif
    <br>
    @php
     $sequence = \App\Setting::getSequence();
     $academicYear = \App\Setting::getAcademicYear();
     $sectionCode = \Illuminate\Support\Facades\Auth::user()->sections_section_code;
    @endphp
    @if(\App\PublishStatus::sequenceResultExistance($sequence->sequence_id,$academicYear,$sectionCode))
    {!! \App\Setting::getAlertWarning(trans('result_management/publish_result.result_published')) !!}
    @endif
    <div class="card">
        <div class="card-body">
            <h2 class="text-center"> @lang('result_management/publish_result.publish_result_title')</h2>

            <a href="{{  trans('settings/routes.publish_result') . trans('settings/routes.publish') }}"
               class="btn c-ewangclarks" style="width: 32%;position: relative;left: 70%;">
                <h6 class="text-white"><i
                        class="zmdi zmdi-globe"></i>@lang('actions/action.publish_result')
                </h6>
            </a>
            {!! $publish_result_exception_list !!}
            <a href="{{  trans('settings/routes.publish_result') . trans('settings/routes.publish') }}"
               class="btn c-ewangclarks" style="position: relative;left: 30%;width:35%; ">
                <h6 class="text-white"><i
                        class="zmdi zmdi-globe"></i>@lang('actions/action.publish_result')
                </h6>
            </a><br><br>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.result_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.publish_result')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

    </script>
@endsection
