<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#ffffff"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
          integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
          crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
    <title>{{ $site_settings->name }}</title>
    <base href="https://{{ $site_settings->domain }}/">
    <script src="/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/line-awesome.css">
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/css/animate.min.css">
    <link rel="stylesheet" href="/css/animated-headline.css">
    <link rel="stylesheet" href="/css/jquery-ui.css">
    <link rel="stylesheet" href="/css/flag-icon.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <link rel="stylesheet" href="/css/mobile.css">
    <link rel="stylesheet" href="/css/childstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="/css/admin.css">
    @livewireStyles
    @stack('styles')
</head>

<body id="fadein" class="fixed-nav" style="padding-top:70px !important; background: rgba(18, 138, 162, 1)">

<header class="header-area">
    @if(session('status'))
        <x-alert message="{{ session('status') }}" variant="primary" role="alert"/>
    @endif
    <div class="header-menu-wrapper d-flex align-items-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="menu-wrapper d-flex">
                        <div class="logo">
                            <a href="/" style="border-radius:5px">
                                <img style="max-height:40px;border-radius:5px;background:transparent;padding:4px;"
                                     src="/img/login_logo.png" alt="logo">
                            </a>
                            <div class="menu-toggler">
                                <i class="la la-bars"></i>
                                <i class="la la-times"></i>
                            </div>
                        </div>
                        <div class="main-menu-content w-100">
                            <div class="align-items-center d-flex justify-content-between gap-3">
                                <div class="w-100">
                                    <nav class="px-5">
                                        <ul class="d-flex justify-content-around" style="padding-top:10px!important">
                                            <li><a class="{{ (request()->is('admin/dashboard*')) ? 'text-primary' : '' }}" href="{{ route('admin.dashboard') }}" title="home">Dashboard</a></li>
                                            <li><a class="{{ (request()->is('admin/sections*')) ? 'text-primary' : '' }}" href="{{ route('admin.sections.index') }}" title="flights">Homepage Sections</a></li>
                                            <li><a class="{{ (request()->is('admin/flights*')) ? 'text-primary' : '' }}" href="{{ route('admin.flights.index') }}" title="hotels">Flights</a></li>
                                            <li><a class="{{ (request()->is('admin/categories*')) ? 'text-primary' : '' }}" href="{{ route('admin.categories.index') }}" title="flights">Categories</a></li>
                                            <li><a class="{{ (request()->is('admin/bookings*')) ? 'text-primary' : '' }}" href="{{ route('admin.bookings') }}" title="cars">Bookings</a></li>
                                            <li><a class="{{ (request()->is('admin/users*')) ? 'text-primary' : '' }}" href="{{ route('admin.users.index') }}" title="visa">Users</a></li>
                                            <li class="dropdown">
                                                <a class="nav-link dropdown-toggle {{ request()->is('admin/locations*') || request()->is('admin/airlines*') || request()->is('admin/contracts*') ? 'text-primary' : '' }}" data-bs-placement="auto" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Settings
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('admin.locations.index') }}">Locations</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.airlines.index') }}">Airlines</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.contracts.index') }}">Contracts</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="d-flex header-top-bar">
                                    <div class="header-right-action pt-1 pe-2">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="ACCOUNT"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg class="pe-1" xmlns="http://www.w3.org/2000/svg" width="20"
                                                     height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                Account<i class="la la-angle-down"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="ACCOUNT">
                                                @if(auth()->user()->type == 'agency')
                                                    <li><a class="dropdown-item" href="/my-bookings">My Bookings</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="/my-profile">My Profile</a></li>
                                                @endif
                                                @if(auth()->user()->type == 'admin' || auth()->user()->type == 'user')
                                                    <li><a class="dropdown-item " href="/admin">Admin Panel</a></li>
                                                @endif
                                                <li><a class="dropdown-item" href="">Logout</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="my-5"></div>
<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
@livewireScripts
<script src="/js/jquery-ui.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/owl.carousel.min.js"></script>
<script src="/js/jquery.fancybox.min.js"></script>
<script src="/js/jquery.countTo.min.js"></script>
<script src="/js/animated-headline.js"></script>
<script src="/js/quantity-input.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="/js/main.js"></script>
<script src="/js/app.js"></script>
@yield('scripts')
@stack('scripts')

<script>
    function closeAlert(event) {
        let element = event.target;
        while (element.nodeName !== "BUTTON") {
            element = element.parentNode;
        }
        element.parentNode.parentNode.removeChild(element.parentNode);
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

</body>

</html>
