@extends('layouts.app')

@section('title')
    @lang('subject_management/view_subject.view_subject_header')
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="@lang('general.data_table')" class="table  table-bordered" cellspacing="0" width="100%">
                    <thead class="thead-default c-ewangclarks text-white">
                    <tr>
                        <th>@lang('subject_management/batch_subject_upload.subject_code')</th>
                        <th>@lang('subject_management/batch_subject_upload.subject_title')</th>
                        <th>@lang('subject_management/batch_subject_upload.coefficient')</th>
                        <th>@lang('subject_management/batch_subject_upload.state')</th>
                        <th>@lang('subject_management/batch_subject_upload.series')</th>
                        <th>@lang('subject_management/batch_subject_upload.class')</th>
                        <th>@lang('subject_management/batch_subject_upload.subject_weight')</th>
                        <th>@lang('subject_management/batch_subject_upload.cycle_name')</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subjects as $subject)
                        <tr>
                            <td style="color: red">{{$subject->subject_code }}</td>
                            <td>{{$subject->subject_title }}</td>
                            <td>{{$subject->coefficient }}</td>
                            <td>{{$subject->state }}</td>
                            <td> {!!  \App\Subject::getSubjectSeriesListById($subject->subject_id) !!}</td>
                            <td>{{\App\AcademicLevel::getClassNameByCode($subject->classes_class_code)}}</td>
                            <td>{{$subject->subject_weight }}</td>
                            <td>{{\App\Program::getCycleNameByCode($subject->programs_program_code)}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ trans('settings/routes.edit_subject'). '/' . \App\Encrypter::encrypt( $subject->subject_id) }}">
                                        <button type="button" class="btn btn-info" vaule="{{$subject->subject_id}}">
                                            <i class="zmdi zmdi-edit zmdi-hc-fw"></i>
                                        </button>
                                    </a>
                                    <a href="{{ trans('settings/routes.delete_subject'). '/' . \App\Encrypter::encrypt( $subject->subject_id) }}">
                                        <button type="button" class="btn btn-info">
                                            <i class="zmdi zmdi-delete zmdi-hc-fw"></i>
                                        </button>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/jquery.battatech.excelexport.js') }}"></script>
    <script src="{{ asset('js/power-school.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.edit_subject')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

    </script>
@endsection
