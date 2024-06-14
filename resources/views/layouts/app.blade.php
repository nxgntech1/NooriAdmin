<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    <?php if(str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true'){ ?> dir="rtl" <?php } ?>>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-small.png') }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icons/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icons/font-awesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icons/font-awesome/css/regular.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icons/font-awesome/css/solid.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <link href="{{ asset('css/colors/blue.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet">

    @php $app_setting = App\Models\Settings::first(); @endphp
	<script src="https://maps.googleapis.com/maps/api/js?key={{$app_setting->google_map_api_key}}&libraries=drawing,geometry,places"></script>

    @yield('style')

    <?php if (isset($_COOKIE['admin_panel_color'])) { ?>

    <style type="text/css">

        .topbar {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .left-sidebar {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }
	.logobackground{
            background: <?php echo $_COOKIE['admin_panel_color'] .' !important'; ?>;
        }

        .sidebar-nav ul li a {
            border-bottom: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav ul li a:hover i {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .restaurant_payout_create-inner fieldset legend {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        a {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        a:hover, a:focus {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        a.link:hover, a.link:focus {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        html body blockquote {
            border-left: 5px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .text-warning {
            color: <?php echo $_COOKIE['admin_panel_color']; ?> !important;
        }

        .text-info {
            color: <?php echo $_COOKIE['admin_panel_color']; ?> !important;
        }

        .sidebar-nav ul li a:hover {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .btn-primary {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
            border: 1px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav > ul > li.active > a {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
            border-left: 3px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav > ul > li.active > a i {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .bg-info {
            background-color: <?php echo $_COOKIE['admin_panel_color']; ?> !important;
        }

        .bellow-text ul li > span {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>

        }

        .table tr td.redirecttopage {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>
        }

        ul.rating {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .nav-tabs.card-header-tabs .nav-link.active, .nav-tabs.card-header-tabs .nav-link:hover {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?> <?php echo $_COOKIE['admin_panel_color']; ?> #fff;
        }

        .btn-warning, .btn-warning.disabled {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
            border: 1px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
            box-shadow: none;
        }

        .payment-top-tab .nav-tabs.card-header-tabs .nav-link.active, .payment-top-tab .nav-tabs.card-header-tabs .nav-link:hover {
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .nav-tabs.card-header-tabs .nav-link span.badge-success {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .nav-tabs.card-header-tabs .nav-link.active span.badge-success, .nav-tabs.card-header-tabs .nav-link:hover span.badge-success, .sidebar-nav ul li a.active, .sidebar-nav ul li a.active:hover, .sidebar-nav ul li.active a.has-arrow:hover, .topbar ul.dropdown-user li a:hover {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .sidebar-nav ul li a.has-arrow:hover::after, .sidebar-nav .active > .has-arrow::after, .sidebar-nav li > .has-arrow.active::after, .sidebar-nav .has-arrow[aria-expanded="true"]::after, .sidebar-nav ul li a:hover {
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>
        }

        [type="checkbox"]:checked + label::before {
            border-right: 2px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
            border-bottom: 2px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .btn-primary:hover, .btn-primary.disabled:hover {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
            border: 1px solid<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .btn-primary.active, .btn-primary:active, .btn-primary:focus, .btn-primary.disabled.active, .btn-primary.disabled:active, .btn-primary.disabled:focus, .btn-primary.active.focus, .btn-primary.active:focus, .btn-primary.active:hover, .btn-primary.focus:active, .btn-primary:active:focus, .btn-primary:active:hover, .open > .dropdown-toggle.btn-primary.focus, .open > .dropdown-toggle.btn-primary:focus, .open > .dropdown-toggle.btn-primary:hover, .btn-primary.focus, .btn-primary:focus, .btn-primary:not(:disabled):not(.disabled).active:focus, .btn-primary:not(:disabled):not(.disabled):active:focus, .show > .btn-primary.dropdown-toggle:focus, .btn-warning:hover, .btn-warning:hover, .btn-warning.disabled:hover, .btn-warning.active.focus, .btn-warning.active:focus, .btn-warning.active:hover, .btn-warning.focus:active, .btn-warning:active:focus, .btn-warning:active:hover, .open > .dropdown-toggle.btn-warning.focus, .open > .dropdown-toggle.btn-warning:focus, .open > .dropdown-toggle.btn-warning:hover, .btn-warning.focus, .btn-warning:focus {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
            box-shadow: 0 0 0 0.2rem<?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .language-options select option, .pagination > li > a.page-link:hover {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .mini-sidebar .sidebar-nav #sidebarnav > li:hover a i, .mini-sidebar .sidebar-nav ul li a, .sidebar-nav ul li a.active i, .sidebar-nav ul li a.active:hover i, .sidebar-nav ul li.active a:hover i {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider .cat-item a.cat-link:hover, .cat-slider .cat-item.section-selected a.cat-link {
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider .cat-item a.cat-link {
            border-bottom-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider .cat-item.section-selected a.cat-link:after {
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .cat-slider {
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .business-analytics .card-box i {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .order-status .data i, .order-status span.count {
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .userlist-top-left a.nav-link {
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
            color: <?php echo $_COOKIE['admin_panel_color']; ?>;
        }

        .userlist-top-left a.nav-link:hover {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
            color: #fff;
        }

        .user-detail .nav.nav-tabs li a {
            border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;
            color: <?php echo $_COOKIE['admin_panel_color']; ?>



        }

        .user-detail .nav.nav-tabs li a:hover, .user-detail .nav.nav-tabs li a.active {
            background: <?php echo $_COOKIE['admin_panel_color']; ?>;
            color: #fff;
        }

        .user-top {
            background-color: <?php echo $_COOKIE['admin_panel_color']; ?> ;
        }

        .sidebar-nav ul li.active a.has-arrow::after, .sidebar-nav ul li a.has-arrow:hover::after {border-color: <?php echo $_COOKIE['admin_panel_color']; ?>;}
        @media screen and ( max-width: 767px ) {

            .mini-sidebar .sidebar-nav ul li a:hover, .sidebar-nav > ul > li.active > a {color: <?php echo $_COOKIE['admin_panel_color']; ?>!important;}

            .mini-sidebar .sidebar-nav #sidebarnav > li:hover a i, .mini-sidebar .sidebar-nav ul li a, .sidebar-nav ul li a.active i, .sidebar-nav ul li a.active:hover i, .sidebar-nav ul li.active a:hover i{color: #fff;}
            .nav-tabs.card-header-tabs .nav-link.active span.badge-success, .nav-tabs.card-header-tabs .nav-link:hover span.badge-success, .sidebar-nav ul li a.active, .sidebar-nav ul li a.active:hover, .sidebar-nav ul li.active a.has-arrow:hover, .topbar ul.dropdown-user li a:hover{color: <?php echo $_COOKIE['admin_panel_color']; ?>}
        }
        .mini-sidebar .sidebar-nav #sidebarnav > li:hover a i {color: <?php echo $_COOKIE['admin_panel_color']; ?> !important;}
    </style>
    <?php } ?>

    <?php if(str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true'){ ?>
    	<link href="{{asset('assets/plugins/bootstrap/css/bootstrap-rtl.min.css')}}" rel="stylesheet">
    <?php } ?>

    <?php if(str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true'){ ?>
    	<link href="{{asset('css/style_rtl.css')}}" rel="stylesheet">
    <?php } ?>
    
</head>

<body>

<div id="app" class="fix-header fix-sidebar card-no-border">
    <div id="main-wrapper">
        <header class="topbar non-printable">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                @include('layouts.header')
            </nav>
        </header>

        <aside class="left-sidebar non-printable">
            <div class="scroll-sidebar">
            @if (Auth::check() && Auth::user()->hasRole('admin'))
                @include('layouts.menu')
            @elseif(Auth::check() && Auth::user()->hasRole('user'))
                @include('layouts.usermenu')
            @endif
            </div>
        </aside>
    </div>
    <main class="py-4">
        @yield('content')
        @include('layouts.footer')
    </main>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('assets/plugins/summernote/summernote.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>

<script type="text/javascript">
    jQuery(window).scroll(function () {
        var scroll = jQuery(window).scrollTop();
        if (scroll <= 60) {
            jQuery("body").removeClass("sticky");
        } else {
            jQuery("body").addClass("sticky");
        }
    });

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        let name = cname + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    $(document).ready(function () {
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        var url = "{{ route('language.header') }}";
        $.ajax({
            url: url,
            type: "GET",
            data: {
                _token: '{{csrf_token()}}',
            },

            dataType: 'json',
            success: function (data) {
                $.each(data, function (key, value) {
                    $('#language_dropdown').append($("<option></option>").attr("value", value.code).text(value.language));
                    //append('<option value="' + value.id + '">' + value.language + '</option>');
                });
                <?php if(session()->get('locale')){ ?>
                $("#language_dropdown").val("<?php echo session()->get('locale'); ?>");
                <?php } ?>
            }
        });


    });

    var url1 = "{{ route('changeLang') }}";

    $(".changeLang").change(function () {
        var slug = $(this).val();
        var url = "{{ route('lang.code',':slugid') }}";
        url = url.replace(':slugid', slug);
        if (slug) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: '{{csrf_token()}}',
                },

                dataType: 'json',
                success: function (data) {

                    $.each(data, function (key, value) {
                        if (value.code == slug) {
                            if (value.is_rtl == false) {
                                setCookie('is_rtl', 'false', 365);
                            } else {
                                 setCookie('is_rtl', value.is_rtl.toString(), 365);
                            }
                            window.location.href = url1 + "?lang=" + value.code;
                        }
                    });
                }
            });
        }

    });

    var url = "{{ route('get-settings') }}";
    $.ajax({
        url: url,
        type: "GET",
        data: {
            _token: '{{csrf_token()}}',
        },
        success: function (data) {

            if ('<?php echo @$_COOKIE['admin_panel_color'];?>' == '' || '<?php echo @$_COOKIE['admin_panel_color'];?>' != data) {
                $('.topbar').css('background', data);
                $('.left-sidebar').css('background', data);
                $('.sidebar-nav ul li a').css('border-bottom', data);
                $('.sidebar-nav').css('background', data);
                $('.sidebar-nav ul li a:hover i').css('color', data);
                $('.restaurant_payout_create-inner fieldset legend').css('background', data);
                $('a').css('color', data);
                $('a:hover, a:focus').css('color', data);
                $('a.link:hover, a.link:focus').css('color', data);
                $('html body blockquote').css('border-left', '5px solid ' + data);
                $('.text-warning').css('color', data);
                $('.text-info').css('color', data);
                $('.sidebar-nav ul li a:hover').css('color', data);
                $('.btn-primary').css({'background': data, 'border': '1px solid ' + data});
                $('.sidebar-nav > ul > li.active > a').css({'color': '#fff', 'border-left': '3px solid ' + data});
                $('.sidebar-nav > ul > li.active > a i').css('color', '#fff');
                $('.bg-info').css('background-color', data + ' !important');
                $('.bellow-text ul li > span').css('color', data);
                $('.table tr td.redirecttopage').css('color', data);
                $('.ul.rating').css('color', data);
                $('.nav-tabs.card-header-tabs .nav-link.active, .nav-tabs.card-header-tabs .nav-link:hover').css({
                    'background': data,
                    'border-color': data + data + '#fff'
                });
                $('.btn-warning, .btn-warning.disabled').css({
                    'background': data,
                    'border': '1px solid ' + data,
                    'box-shadow': 'none'
                });
                $('.payment-top-tab .nav-tabs.card-header-tabs .nav-link.active, .payment-top-tab .nav-tabs.card-header-tabs .nav-link:hover').css('border-color', data);
                $('.nav-tabs.card-header-tabs .nav-link span.badge-success').css('background', data);
                $('.nav-tabs.card-header-tabs .nav-link.active span.badge-success, .nav-tabs.card-header-tabs .nav-link:hover span.badge-success, .sidebar-nav ul li a.active, .sidebar-nav ul li a.active:hover, .sidebar-nav ul li.active a.has-arrow:hover, .topbar ul.dropdown-user li a:hover').css('color', data);
                $('.sidebar-nav ul li a.has-arrow:hover::after, .sidebar-nav .active > .has-arrow::after, .sidebar-nav li > .has-arrow.active::after, .sidebar-nav .has-arrow[aria-expanded="true"]::after, .sidebar-nav ul li a:hover').css('border-color', data + ' !important');
                $('[type="checkbox"]:checked + label::before').css({
                    'border-right': '2px solid ' + data,
                    'border-bottom': '2px solid ' + data
                });

                $('.btn-primary:hover, .btn-primary.disabled:hover').css({
                    'background': data,
                    'border': '1px solid ' + data
                });

                $('.btn-primary.active, .btn-primary:active, .btn-primary:focus, .btn-primary.disabled.active, .btn-primary.disabled:active, .btn-primary.disabled:focus, .btn-primary.active.focus, .btn-primary.active:focus, .btn-primary.active:hover, .btn-primary.focus:active, .btn-primary:active:focus, .btn-primary:active:hover,' +
                    ' .open > .dropdown-toggle.btn-primary.focus, .open > .dropdown-toggle.btn-primary:focus, .open > .dropdown-toggle.btn-primary:hover, .btn-primary.focus, .btn-primary:focus, .btn-primary:not(:disabled):not(.disabled).active:focus, .btn-primary:not(:disabled):not(.disabled):active:focus, ' +
                    '.show > .btn-primary.dropdown-toggle:focus, .btn-warning:hover, .btn-warning:hover, .btn-warning.disabled:hover, .btn-warning.active.focus, .btn-warning.active:focus, .btn-warning.active:hover, .btn-warning.focus:active, .btn-warning:active:focus, .btn-warning:active:hover, .open > .dropdown-toggle.btn-warning.focus,' +
                    ' .open > .dropdown-toggle.btn-warning:focus, .open > .dropdown-toggle.btn-warning:hover, .btn-warning.focus, .btn-warning:focus').css({
                    'background': data,
                    'border-color': data,
                    'box-shadow': '0 0 0 0.2rem ' + data
                });


                $('.language-options select option, .pagination > li > a.page-link:hover').css('background', data);

                $('.sidebar-nav ul li a').css('color', '#fff');
                $('.mini-sidebar .sidebar-nav #sidebarnav > li:hover a i, .sidebar-nav ul li a.active i, .sidebar-nav ul li a.active:hover i, .sidebar-nav ul li.active a:hover i').css('color', data);

                $('.cat-slider .cat-item a.cat-link:hover, .cat-slider .cat-item.section-selected a.cat-link').css('border-color', data);

                $('.cat-slider .cat-item a.cat-link').css('border-bottom-color', data);
                $('.cat-slider .cat-item.section-selected a.cat-link:after').css({
                    'border-color': data,
                    'background': data
                });
                $('.cat-slider').css({'border-color': data});
                $('.business-analytics .card-box i').css({'background': data});
                $('.order-status .data i, .order-status span.count').css({'color': data});
                $('.userlist-top-left a.nav-link').css({'color': data, 'border-color': data});
                $('.userlist-top-left a.nav-link:hover').css({'color': '#fff', 'background': data});
                $('.user-detail .nav.nav-tabs li a').css({'color': data, 'border-color': data});
                $('.user-detail .nav.nav-tabs li a:hover, .user-detail .nav.nav-tabs li a.active').css({
                    'color': '#fff',
                    'background': data
                });
                $('.user-top').css('background-color', data);

            } else {

            }
            setCookie('admin_panel_color', data, 365);
        }
    });

</script>

@yield('scripts')

</body>
</html>
