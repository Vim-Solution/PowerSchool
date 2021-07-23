<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', trans('settings/setting.app_name')) }}</title>


    <!-- Vendor styles -->
    <link rel="stylesheet"
          href="{{ asset('template/vendors/material-design-iconic-font/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/jquery-scrollbar/jquery.scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/select2/css/select2.min.css') }}">
    <script src="{{ asset('template/vendors/jquery/jquery.min.js') }}"></script>

    <!-- App styles -->
    <link rel="stylesheet" href="{{ asset('template/css/app.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body data-ma-theme="{{ trans('settings/theme.app_theme') }}">
<header class="header" style="height: 100px;position: static;">
    <div class="navigation-trigger hidden-xl-up" data-ma-action="aside-open" data-ma-target=".sidebar">
        <div class="navigation-trigger__inner">
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
            <i class="navigation-trigger__line"></i>
        </div>
    </div>

    <div class="header__logo hidden-sm-down" style="position: absolute;left: 1%;">
        <img class="img-circle" src="{{ asset( trans('img/img.school_logo')) }}" alt="" height="70px" width="80px"
             style="border-radius: 10%"/>
    </div>
    <div>

    </div>

    <ul class="top-nav">
        @guest
            <a href="{{ trans('settings/routes.result_portal') }}" title="@lang('auth.tooltip_text')"
               data-toggle="tooltip" data-placement="left">
                <h5 class="text-white"><i class="zmdi zmdi-arrow-forward"></i> @lang('general.student_portal')</h5>
            </a>
        @endguest

        @auth
            <a href="{{ trans('settings/routes.home') }}" data-placement="left">
                <h5 class="text-white">@lang('general.home')</h5>
            </a>

        @endauth
    </ul>
</header>

<main class="main" style="padding-top: 1%">
    <div class="row" style="position: relative;left: 3%;">
        <div class="col-ewangclarks-9">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center"><b>@lang('settings/setting.school_caption')</b></h4>
                    <div id="carouselExampleCaption" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleCaption" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleCaption" data-slide-to="1"></li>
                            <li data-target="#carouselExampleCaption" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img src="{{ asset( trans('img/img.slide1')) }}" alt="First slide"
                                     height="300px"
                                     width="250px;">
                                <div class="carousel-caption">
                                    <h3>{{ trans('settings/setting.slide_1') }}</h3>
                                    <p>{{ trans('settings/setting.slide_1_t') }}</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset( trans('img/img.slide2')) }}" alt="Second slide"
                                     height="300px"
                                     width="250px;">
                                <div class="carousel-caption">
                                    <h3>{{ trans('settings/setting.slide_2') }}</h3>
                                    <p>{{ trans('settings/setting.slide_2_t') }}</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset(  trans('img/img.slide3'))  }}" alt="Third slide"
                                     height="300px"
                                     width="250px;">
                                <div class="carousel-caption">
                                    <h3>{{ trans('settings/setting.slide_3') }}</h3>
                                    <p>{{ trans('settings/setting.slide_3_t') }}</p>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleCaption" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleCaption" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="toolbar">
                <div class="toolbar__label" style="width: 80%;">
                    <div class="row">
                        <div class="c-ewangclarks" style="padding-left: 30px;padding-right: 20px;padding-bottom:10px;">
                            <br>
                            <label class="text-white">{{ ucfirst(trans('general.set_language'))}}</label>
                        </div>
                        <div style="width: 30%;padding-left: 15px;z-index: 11;">
                            <p></p>
                            <form action="{{ trans('settings/routes.change_locale') }}" method="get" id="change-lo"
                                  enctype="multipart/form-data">
                                <select class="select2" data-placeholder="{{ trans('general.select_language') }}"
                                        name="change-locale" id="change-locale">
                                    <option value="none" selected
                                            disabled>{{ ucfirst(trans('general.select_language')) }}</option>
                                    <option value="en">{{ ucfirst(trans('general.en'))}}</option>
                                    <option value="fr">{{ ucfirst(trans('general.fr'))}}</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <i class="actions__item zmdi zmdi-search" data-ma-action="toolbar-search-open"></i>
                </div>
            </div>
        </div>
        <div class="col-ewangclarks-3">
            <div class="card">
                <div class="card-header c-ewangclarks" style="height: 56%;">
                    <div class="text-center">
                        <h2 class=" text-white"><b>@lang('general.app_name')</b></h2>
                        <img src="{{ asset( trans('img/img.school_logo')) }}" class="widget-profile__img" alt=""
                             height="10px" width="15px">
                        <h4 class=" text-white"><b>@lang('settings/setting.school_name')</b></h4>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">

                            <label for="email" class="form-label ">{{ trans('auth.email') }}</label>

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   value="{{ old('email') }}" required autocomplete="email">

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">{{ trans('auth.password') }}</label>

                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" required autocomplete="new-password">

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary c-ewangclarks"
                                style="width: 100%;padding-top: 5px;padding-bottom: 5px; ">
                            <h6 class="text-white">@lang('auth.login')</h6>
                        </button>
                        <br><br><br>
                    </form>
                    <br><br>
                </div>
            </div>
        </div>

    </div>
</main>

<header class="header" id="footer" data-ma-theme="{{ trans('settings/theme.app_theme') }}">
    <div class="header__logo hidden-sm-down" style="position: absolute;left: 1%;">
        <a class="text-white" href="@lang('settings/routes.company')">@lang('general.company')</a><br>
        +237673676301
    </div>
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
            </div>
            <div class="col l4  s12">
            </div>
            <div class="col l2 s12">

            </div>
        </div>
    </div>
    <div class="footer-copyright">
            © 2018 Copyright
            <p>© @lang('general.app_name'). @lang('general.ownership')</p>
    </div>

    <ul class="top-nav">
    </ul>
</header>

<style type="text/css">
    #footer {
        position: relative;
        top: 5%;
    }

    .widget-profile__img {
        width: 90px;
        height: 80px;
        border-radius: 100%;
        margin-bottom: 1rem;
    }

    .c-ewangclarks {
        background-color: @lang('settings/theme.c-ewangclarks');
        color: white;
    }

    .c-ewangclarks:hover {
        background-color: @lang('settings/theme.c-ewangclarks');
        color: white;
    }

    .c-ewangclarks:active {
        background-color: @lang('settings/theme.c-ewangclarks');
        color: white;
    }

    .c-ewangclarks:focus {
        background-color: @lang('settings/theme.c-ewangclarks');
        color: white;
    }
</style>
<script type="text/javascript">
    $('#change-locale').change(function () {
        $('#change-lo').submit();
    });
</script>

<!-- Javascript -->
<!-- Vendors -->
<script src="{{ asset('template/vendors/popper.js/popper.js') }}"></script>
<script src="{{ asset('template/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('template/vendors/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('template/vendors/jquery-scrollLock/jquery-scrollLock.min.js') }}"></script>
<script src="{{ asset('template/vendors/select2/js/select2.full.min.js') }}"></script>

<!-- App functions and actions -->
<script src="{{ asset('template/js/app.min.js') }}"></script>
</body>
</html>

