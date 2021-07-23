@extends('layouts.app')

@section('title')
    @lang('result_management/publish_result.change_password')
@endsection

@section('content')
    <br><br>
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
    <div class="card team__item">
        <a href="{{ trans('settings/routes.publish_result') }}" class="btn c-ewangclarks btn--icon"
           style="position: relative;left:95%;top: 100%;"><i class="zmdi zmdi-arrow-back"></i></a>
        <div class="row groups" style="position:relative;left: 35%;">
            <div class="col-xl-4 col-lg-4 col-sm-4 col-6">
                <div class="groups__item bg-indigo">
                    <div class="groups__img">
                        @foreach($teachers as $teacher)
                            <img class="avatar-img" src="{{ asset($teacher->profile) }}" alt="">
                        @endforeach
                    </div>
                    <div class="groups__info">
                        <strong>
                            <ul>
                                @foreach($teachers as $teacher)
                                    <li style="color: white">{{ $teacher->full_name }}</li>
                                @endforeach
                            </ul>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card-header c-ewangclarks">
                <h1 class="card-title text-white">@lang('result_management/publish_result.notification_title',['class' => \App\AcademicLevel::getClassNameByCode($subject->classes_class_code),'subject' => $subject->subject_title])</h1>
            </div>
            <br><br>
                <form method="post"
                      action="{{ trans('settings/routes.publish_result') .  trans('settings/routes.notify') . '/' . \App\Encrypter::encrypt($subject->subject_id)}}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="width: 40%;position:relative;left: 33%;">
                    <div class="col-md-12 text-left">
                        <div class="form-group">
                            <label>{{ trans('result_management/publish_result.subject') }}</label>
                            <input id="subject" type="text" class="form-control" name="subject" required>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="col-md-12 text-left">
                        <div class="form-group">
                            <label for="body">{{ trans('result_management/publish_result.body') }}</label>
                            <textarea class="form-control fg-line" name="body" id="body"></textarea>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>


                    <button type="submit" class="btn c-ewangclarks"
                            style="width: 100%;padding-top: 6px;padding-bottom: 5px; ">
                        <h6 class="text-white"><i class="zmdi zmdi-notifications-add"></i>@lang('actions/action.notify')
                        </h6>
                    </button>
                    </div>
                    <br><br><br>
                </form>
                <br>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var catName = '#' + "<?php echo trans('authorization/category.result_management') ?>";
        var privName = '#' + "<?php echo trans('authorization/privilege.publish_result')?>";
        catId = catName.replace(/ /g, "_");
        privId = privName.replace(/ /g, "_");

        $(privId).addClass('navigation__active');
        $(catId).addClass('navigation__sub--active navigation_sub--toggled');

    </script>
@endsection

