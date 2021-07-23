

<footer class="footer hidden-xs-down">
    <p>© @lang('general.app_name'). @lang('general.ownership')</p>

    <ul class="nav footer__nav">
        <a class="nav-link" href="@lang('settings/routes.company')">@lang('general.company')</a>
    </ul>
</footer>

<!-- Older IE warning message -->
<!--[if IE]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade to any of the following web browsers to
        access this website.</p>

    <div class="ie-warning__downloads">
        <a href="http://www.google.com/chrome">
            <img src="img/browsers/chrome.png" alt="">
        </a>

        <a href="https://www.mozilla.org/en-US/firefox/new">
            <img src="img/browsers/firefox.png" alt="">
        </a>

        <a href="http://www.opera.com">
            <img src="img/browsers/opera.png" alt="">
        </a>

        <a href="https://support.apple.com/downloads/safari">
            <img src="img/browsers/safari.png" alt="">
        </a>

        <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
            <img src="img/browsers/edge.png" alt="">
        </a>

        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
            <img src="img/browsers/ie.png" alt="">
        </a>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->

<!-- Javascript -->
<!-- Vendors -->
<script src="{{ asset('template/vendors/popper.js/popper.min.js') }}"></script>
<script src="{{ asset('template/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('template/vendors/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('template/vendors/jquery-scrollLock/jquery-scrollLock.min.js') }}"></script>
@yield('script')
<!-- App functions and actions -->
<script src="{{ asset('template/js/app.min.js') }}"></script>

