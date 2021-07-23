@extends('layouts.app')


@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/vims-file-upload.css') }}">
@endsection

@section('title')
    @lang('general.profile')
@endsection

@section('content')

    <div class="card profile">
        <div class="profile__img">
            <img src="{{asset(\Illuminate\Support\Facades\Auth::user()->profile)}}" alt="" height="150" width="200">
            <form enctype="multipart/form-data" method="post"
                  action="{{ trans('settings/routes.home')}}"
                  id="change-profile">
                @csrf()
                    <label for="profile"
                           style="padding-top:5px;padding-bottom: 0px;padding-left:15px;margin: 0px;color: red;font-size: 10px;"
                    ><i class="zmdi zmdi-camera profile__img__edit"></i></label>
                <input type="file" class="vims-file-input" name="profile-picture" value=""
                       id="profile">
            </form>
        </div>

        <div class="profile__info">
            <p style="color: #0D0A0A;"><b>{{ trans('profile.position') }}</b></p>
            <ul class="icon-list">
                <li><i class="zmdi zmdi-graduation-cap"></i> {{ \Illuminate\Support\Facades\Auth::user()->position }}
                </li>
                <li><i class="zmdi zmdi-phone"></i> {{ \Illuminate\Support\Facades\Auth::user()->phone_number }}</li>
                <li><i class="zmdi zmdi-email"></i> {{ \Illuminate\Support\Facades\Auth::user()->email }}</li>
                <li><i class="zmdi zmdi-my-location"></i> {{ \Illuminate\Support\Facades\Auth::user()->office_address }}
                </li>
            </ul>
        </div>
    </div>

    <div class="toolbar">
        <nav class="toolbar__nav">
            <h6 class="active">@lang('profile.general_info')</h6>
        </nav>

        <div class="actions">
            <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>
        </div>

        <div class="toolbar__search">
            <input type="text" placeholder="Search...">

            <i class="toolbar__search__close zmdi zmdi-long-arrow-left" data-ma-action="toolbar-search-close"></i>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-body__title mb-4">{{ trans('profile.about_caption',['name' => strtok(ucfirst(strtolower(\Illuminate\Support\Facades\Auth::user()->full_name)),' ') . ' ' . ucfirst(strtolower(strtok(' ')))]) }}</h4>

            <p> {{  trans('profile.about_text' ,['name' => strtok(ucfirst(strtolower(\Illuminate\Support\Facades\Auth::user()->full_name)),' ') . ' ' . ucfirst(strtolower(strtok(' '))),'post' =>  \Illuminate\Support\Facades\Auth::user()->type . ' ' . \Illuminate\Support\Facades\Auth::user()->position,'school' => trans('settings/setting.school_name')]) }}</p>


            <br>

            <h4 class="card-body__title mb-4">@lang('profile.contact_caption')</h4>

            <ul class="icon-list">
                <li><i class="zmdi zmdi-phone"></i> {{ \Illuminate\Support\Facades\Auth::user()->phone_number }}</li>
                <li><i class="zmdi zmdi-email"></i> {{ \Illuminate\Support\Facades\Auth::user()->email }}</li>
                <li><i class="zmdi zmdi-my-location"></i> {{ \Illuminate\Support\Facades\Auth::user()->address }}</li>
            </ul>
            <br><br>

            <h4 class="card-body__title mb-4">@lang('profile.school_info')</h4>

            <ul class="icon-list">
                <li>
                    <i class="zmdi zmdi-pin"></i> {{ App\SchoolSection::getSectionNameByCode(\Illuminate\Support\Facades\Auth::user()->sections_section_code) }}
                </li>
                <li>
                    <i class="zmdi zmdi-pin-drop"></i> {{ App\Program::getCycleNameByCode(\Illuminate\Support\Facades\Auth::user()->programs_program_code) }}
                </li>
                <li><i class="zmdi zmdi-graduation-cap"></i> {{ \Illuminate\Support\Facades\Auth::user()->position }}
                </li>
                <li><i class="zmdi zmdi-my-location"></i> {{ \Illuminate\Support\Facades\Auth::user()->office_address }}
                </li>
                <li>
                    <i class="zmdi zmdi-map"></i> {{ App\Department::getDepartmentNameById(\Illuminate\Support\Facades\Auth::user()->departments_department_id) }}
                </li>
                <li><i class="zmdi zmdi-account-calendar"></i> {{ \Illuminate\Support\Facades\Auth::user()->type }}</li>
                <li>
                    <i class="zmdi zmdi-nature-people"></i> {{\App\Role::getRoleNameById(\Illuminate\Support\Facades\Auth::user()->roles_role_id) }}
                </li>
            </ul>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/power-school.js') }}"></script>

    <script type="text/javascript">
        $('#home').addClass('navigation__active')
        $('#profile').change(function (e) {
            $('#change-profile').submit();
        });
    </script>
@endsection
