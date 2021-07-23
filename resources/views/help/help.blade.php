@extends('layouts.app')

@section('title')
    @lang('help/help.help_header')
@endsection

@section('content')
    <header><br>
        <h4> @lang('help/help.help_header')</h4>
        <small style="font-size: 12px;">@lang('help/help.help_title')</small>
    </header><br>
    <div class="row">
        <div class="col-md-8">
            @guest
                <div class="card">
                    <div class="card-header"><h5>@lang('help/help.faq_title_1')</h5></div>
                    <div class="card-body">
                        <p class="card-text">@lang('help/help.faq_1')</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h5>@lang('help/help.faq_title_2')</h5></div>
                    <div class="card-body">
                        <p class="card-text">@lang('help/help.faq_2')</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h5>@lang('help/help.faq_title_3')</h5></div>
                    <div class="card-body">
                        <p class="card-text">@lang('help/help.faq_3')</p>
                    </div>
                </div>
            @endguest
            <div class="card">
                <div class="card-header"><h5>@lang('help/help.faq_title_4')</h5></div>
                <div class="card-body">
                    <p class="card-text">@lang('help/help.faq_4')</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h5>@lang('help/help.faq_title_5')</h5></div>
                <div class="card-body">
                    <p class="card-text">@lang('help/help.faq_5')</p>
                </div>
            </div>
            @auth
                <div class="card">
                    <div class="card-header"><h5>@lang('help/help.faq_title_6')</h5></div>
                    <div class="card-body">
                        <p class="card-text">@lang('help/help.faq_6')</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h5>@lang('help/help.faq_title_7')</h5></div>
                    <div class="card-body">
                        <p class="card-text">@lang('help/help.faq_7')</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h5>@lang('help/help.faq_title_8')</h5></div>
                    <div class="card-body">
                        <p class="card-text">@lang('help/help.faq_8')</p>
                    </div>
                </div>
            @endauth
        </div>
        <div class="col-md-4">
            <div class="card">
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
                <form method="post" action="{{ trans('settings/routes.help') }}" enctype="multipart/form-data">
                    @csrf()
                    <div class="card-body">
                        <h4 class="card-title">@lang('help/help.write_to_us')</h4>
                        <h6 class="card-subtitle">@lang('help/help.write_to_us_caption')</h6>

                        <div class="form-group">
                            <input type="text" class="form-control"
                                   placeholder="@lang('settings/setting.school_acronym')" name="school-acronym" readonly >
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="@lang('settings/setting.vims_email')"  name="email"
                                   readonly>
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" placeholder="@lang('help/help.contact')" name="contact">
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="@lang('help/help.title')" name="title">
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="@lang('help/help.message')"
                                      name="message"></textarea>
                            <i class="form-group__bar"></i>
                        </div>

                        <p class="mb-5">@lang('help/help.terms_privacy')</p>

                        <button class="btn btn-primary" type="submit" style="width: 100%;">@lang('actions/action.submit')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

