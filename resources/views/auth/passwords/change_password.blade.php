@extends('layouts.app')

@section('title')
    @lang('passwords.change_password')
@endsection

@section('content')
    <br><br><br><br>
    <div class="card team__item">
        <img src="{{asset(\Illuminate\Support\Facades\Auth::user()->profile)}}" class="team__img" alt="">

        <div class="card-body">
            <h4 class="card-title">{{\Illuminate\Support\Facades\Auth::user()->full_name}}</h4><br>
            <div class="container" style="width: 50%">
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
                <form method="post" action="{{ trans('settings/routes.change_password') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="password" class="form-label">{{ trans('passwords.old_password') }}</label>

                        <input id="old-password" type="password"
                               class="form-control"
                               name="old-password" required autocomplete="old-password">
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">{{ trans('passwords.new_password') }}</label>
                        <input id="new-password" type="password"
                               class="form-control"
                               name="new-password" required autocomplete="new-password">

                    </div>

                    <button type="submit" class="btn c-ewangclarks"
                            style="width: 100%;padding-top: 6px;padding-bottom: 5px; ">
                        <h6 class="text-white">@lang('passwords.change_password')</h6>
                    </button>
                    <br><br><br>
                </form>
                <br><br>
            </div>
        </div>
    </div>
    </div>

@endsection
