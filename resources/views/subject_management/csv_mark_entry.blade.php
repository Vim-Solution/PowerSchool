@extends('layouts.app')

@section('stylesheets')
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
        <div class="card-body">
            <h1 class="card-title">{{ trans('subject_management/manage_test.csv_mark_entry_header',['test' => $test->test_name,'subject' => $subject->subject_title,'class' => \App\AcademicLevel::getClassNameByCode($subject->classes_class_code)]) }}</h1>
            <a href="{{ trans('settings/routes.generate_student_list') }}" class="btn btn-success"
               style="width: 30%;position: absolute;right: 2%;top: 15%;"><i
                    class="zmdi zmdi-download"></i> @lang('subject_management/manage_test.download_sample')
            </a><br>

            <form enctype="multipart/form-data" method="post"
                  action="{{  trans('settings/routes.manage_subject_test') . trans('settings/routes.csv_mark_entry') . '/' . \App\Encrypter::encrypt($test->test_id) }}"
                  class="vims-file-upload" id="upload-file">
                @csrf()
                <input type="file" name="marks-field" class="vims-file-input" id="vims-file-upload" multiple>
                <label for="vims-file-upload" class="btn vims-z-depth-4"> <i class="zmdi zmdi-cloud-upload"
                                                                             style="font-size: 22px"></i> @lang('subject_management/manage_test.drop_zone_text')
                </label>
                <br><br><br><br><br>
            </form>
            <br>

            <button class="btn btn-primary" style="width: 30%;position: relative;left: 35%;"
                    id="vims-file-upload-submit">@lang('subject_management/manage_test.file_upload_text')
            </button>
            <br><br><br>
            <a class="btn bg-red  text-white " style="position: relative;left: 68%;width: 33%;"
               href="{{ trans('settings/routes.manage_subject_test')  . '/' . \App\Encrypter::encrypt($subject->subject_id) }}"><i
                    class="zmdi zmdi-arrow-back"></i> @lang('subject_management/manage_test.change_test')
            </a><br><br><br>

        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('js/power-school.js') }}"></script>
    <script src="{{ asset('js/vims-file-upload.js') }}"></script>

    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.subject_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.manage_subject_test')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

        $('.zmdi-search').closest(".toolbar").find(".toolbar__search").fadeIn(200);
        $('.zmdi-search').closest(".toolbar").find(".toolbar__search input").focus();

    </script>

@endsection
