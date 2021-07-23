
<aside class="sidebar">
    <div class="scrollbar-inner">
        <div class="user">
            <div class="user__info" data-toggle="dropdown">
                <img class="user__img" src="{{asset(\Illuminate\Support\Facades\Auth::user()->profile)}}" alt="">
                <div>
                    <div class="user__name">{{ strtok(ucfirst(strtolower(\Illuminate\Support\Facades\Auth::user()->full_name)),' ') . ' ' . ucfirst(strtolower(strtok(' '))) }}</div>
                    <div class="user__email">{{ \Illuminate\Support\Facades\Auth::user()->email}}</div>
                </div>
            </div>

            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ trans('settings/routes.home') }}">@lang('general.view_profile')</a>
                <a class="dropdown-item" href="{{ trans('settings/routes.change_password') }}">@lang('general.change_password')</a>
                <a class="dropdown-item" href="{{ trans('settings/routes.logout') }}">@lang('general.logout')</a>
            </div>
        </div>

        <ul class="navigation">
            <li id="home"><a href="{{ trans('settings/routes.home') }}"><i class="zmdi zmdi-home"></i>@lang('general.home')</a></li>
            <!-- get all user privilges and display them below -->
            {!! \App\Role::getCategorizedPrivileges(\Illuminate\Support\Facades\Auth::user()->roles_role_id) !!}
        </ul>
    </div>
</aside>

