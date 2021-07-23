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

    <br><br>
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

    <div class="card">
        <div class="card-body">
            <table id="@lang('general.data_table')" class="table table-striped">
                <thead class="thead-default c-ewangclarks text-white">
                <tr>
                    <th>@lang('student_management/generate_id.matricule')</th>
                    <th>@lang('student_management/generate_id.full_name')</th>
                    <th>@lang('student_management/generate_id.date_of_birth')</th>
                    <th>@lang('student_management/generate_id.place_of_birth')</th>
                    <th>@lang('student_management/generate_id.class')</th>
                    <th>@lang('student_management/generate_id.generate_id')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{$student->matricule }}</td>
                        <td>{{$student->full_name }}</td>
                        <td>{{$student->date_of_birth }}</td>
                        <td>{{$student->place_of_birth }}</td>
                        <td>{{\App\Student::getStudentClassNameByMatricule($student->matricule)}}</td>
                        <td>
                            <a class="btn btn-primary" style="width: 60%;"
                               href="{{ trans('settings/routes.id_card'). '/' . \App\Encrypter::encrypt( $student->student_id) }}">
                                <i class="zmdi zmdi-wrench"></i> {{ trans('student_management/generate_id.id_card') }}
                            </a>

                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>


        @endsection

        @section('script')
            <script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/dataTables.buttons.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/buttons.print.min.js') }}"></script>
            <script src="{{ asset('template/vendors/jszip/jszip.min.js') }}"></script>
            <script src="{{ asset('template/vendors/datatables-buttons/buttons.html5.min.js') }}"></script>

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
