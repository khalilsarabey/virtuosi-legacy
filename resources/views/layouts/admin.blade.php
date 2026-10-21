<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#ffffff"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
    <title>{{ $site_settings->name }}</title>
    <script src="/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/line-awesome.css">
    <link rel="stylesheet" href="/css/jquery-ui.css">
    <link rel="stylesheet" href="/css/flag-icon.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js" integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/basic.min.css" integrity="sha512-MeagJSJBgWB9n+Sggsr/vKMRFJWs+OUphiDV7TJiYu+TNQD9RtVJaPDYP8hA/PAjwRnkdvU+NsTncYTKlltgiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/mobile.css">
    <link rel="stylesheet" href="/css/childstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="/css/admin.css">
    @livewireStyles
    <x-brand-colors />
    <style>
        .admin-header {
            background: {{ $site_settings->primary_color ?? '#000000' }}
        }
    </style>
    @stack('styles')
</head>

<body id="fadein" class="fixed-nav" style="padding-top:70px !important;">

<header class="header-area">
    @if(session('status'))
        <x-alert message="{{ session('status') }}" variant="indigo" role="alert"/>
    @endif
    <div class="header-menu-wrapper d-flex align-items-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="menu-wrapper d-flex">
                        <div class="logo">
                            <a href="/" style="border-radius:5px">
                                @if(count($site_settings->logo))
                                    <img style="max-height:40px;border-radius:5px;background:transparent;padding:4px;" src="{{ $site_settings->logo->first()['url'] }}" alt="logo">
                                @else
                                    <h5>{{ $site_settings->name }}</h5>
                                @endif
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
                                            <li><a class="{{ (request()->is('v2/admin')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.dashboard') }}" title="home">Dashboard</a></li>
                                            @if(auth()->user()->type != 'accounting')
                                                <li><a class="{{ (request()->is('v2/admin/updates*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.updates.index') }}" title="flights">Updates</a></li>
                                            @endif
                                            @if(auth()->user()->type == 'admin')
                                                <li><a class="{{ (request()->is('v2/admin/pages*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.pages.index') }}" title="pages">Pages</a></li>
                                                <li><a class="{{ (request()->is('v2/admin/sections*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.sections.index') }}" title="flights">Homepage Sections</a></li>
                                                <li><a class="{{ (request()->is('v2/admin/categories*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.categories.index') }}" title="flights">Categories</a></li>
                                            @endif
                                            @if(auth()->user()->type == 'white-admin')
                                                <li><a class="{{ (request()->is('v2/admin/blocks*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.blocks.index') }}" title="flights">Home Builder</a></li>
                                            @endif
                                            @if(auth()->user()->type != 'white-admin' && auth()->user()->type != 'accounting')
                                                @if(config('features.charter'))
                                                    <li><a class="{{ (request()->is('v2/admin/flights*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.flights.index') }}" title="hotels">Flights</a></li>
                                                @endif
                                                @if(config('features.hotels'))
                                                    <li><a class="{{ (request()->is('v2/admin/hotels*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.hotels.index') }}" title="hotels">Hotels</a></li>
                                                @endif
                                            @endif
                                            @if(config('features.charter') || config('features.lowcost') || config('features.hotels'))
                                                <li class="dropdown">
                                                    <a class="nav-link dropdown-toggle {{ request()->is('v2/admin/bookings*') ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" data-bs-placement="auto" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Bookings
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        @if(config('features.charter'))
                                                            <li><a class="dropdown-item" href="{{ route('admin.bookings') }}">Charter</a></li>
                                                        @endif
                                                        @if(config('features.lowcost') && auth()->user()->agency_id == 1)
                                                            <li><a class="dropdown-item" href="{{ route('admin.airline-bookings.index') }}">Low Cost</a></li>
                                                        @endif
                                                        @if(config('features.hotels'))
                                                            <li><a class="dropdown-item" href="{{ route('admin.bookings.hotels') }}">Hotels</a></li>
                                                        @endif
                                                        @if(config('features.packages'))
                                                            <li><a class="dropdown-item" href="{{ route('admin.bookings.packages') }}">Packages</a></li>
                                                        @endif
                                                    </ul>
                                                </li>
                                            @endif
                                            @if(auth()->user()->type != 'accounting')
                                                <li><a class="{{ (request()->is('v2/admin/users*')) ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" href="{{ route('admin.users.index') }}" title="visa">Users</a></li>
                                            @endif
                                            @if(auth()->user()->agency_id == 1 && auth()->user()->type == 'admin')
                                                @if(config('features.autopricing') && $site_settings->autopricing == 1 && auth()->user()->agency_id == 1)
                                                    <li class="dropdown">
                                                        <a class="nav-link dropdown-toggle {{ request()->is('admin/locations*') || request()->is('admin/airlines*') || request()->is('admin/contracts*') ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" data-bs-placement="auto" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Auto Pricing
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="{{ route('admin.settings.auto-pricing.classes.index') }}">Classes</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.settings.auto-pricing.pricing.index') }}">Custom Pricing</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.settings.auto-pricing.agencies.index') }}">Agencies</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.settings.auto-pricing.airlines.index') }}">Airlines</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.settings.auto-pricing.categories.index') }}">Categories</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.turkish.sections.index') }}">Turkish</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('admin.settings.auto-pricing.announcements.index') }}">Announcements</a></li>
                                                        </ul>
                                                    </li>
                                                @endif
                                            @endif
                                            @if(auth()->user()->type != 'accounting')
                                                <li class="dropdown">
                                                <a class="nav-link dropdown-toggle {{ request()->is('v2/admin/locations*') || request()->is('v2/admin/airlines*') || request()->is('v2/admin/contracts*') ? 'text-primary' : '' }} text-uppercase font-size-14 font-weight-bold" data-bs-placement="auto" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Settings
                                                </a>
                                                <ul class="dropdown-menu">
                                                    @if(auth()->user()->type != 'white-admin')
                                                        <li><a class="dropdown-item" href="{{ route('admin.locations.index') }}">Locations</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('admin.airlines.index') }}">Airlines</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('admin.contracts.index') }}">Contracts</a></li>
                                                        @if(config('features.lowcost'))
                                                        <li><a class="dropdown-item" href="{{ route('admin.integrations.index') }}">Integrations</a></li>
                                                        @endif
                                                        @if(config('features.whitelabel'))
                                                        <li><a class="dropdown-item" href="{{ route('admin.whitelabel.index') }}">White Label</a></li>
                                                        @endif
                                                        @if(config('features.connections'))
                                                            <li><a class="dropdown-item" href="{{ route('admin.connections.index') }}">Connections</a></li>
                                                        @endif
                                                    @endif
                                                    <li><a class="dropdown-item" href="{{ route('admin.settings.branding') }}">My Agency</a></li>
                                                </ul>
                                            </li>
                                            @endif
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
                                                <li><a class="dropdown-item text-center" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                                                <li>
                                                    <form action="{{route('logout')}}" method="post">
                                                        @csrf
                                                        <button class="dropdown-item text-left" href="">Logout</button>
                                                    </form>
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
{{ $slot }}
<div class="my-5"></div>
<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js" integrity="sha512-TPh2Oxlg1zp+kz3nFA0C5vVC6leG/6mm1z9+mA81MI5eaUVqasPLO8Cuk4gMF4gUfP5etR73rgU/8PNMsSesoQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@livewireScripts
<script src="/js/jquery-ui.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.1/dist/livewire-sortable.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
    .flatpickr-calendar {
        background: transparent;
        opacity: 0;
        display: none;
        text-align: center;
        visibility: hidden;
        padding: 0;
        -webkit-animation: none;
        animation: none;
        direction: ltr;
        border: 0;
        font-size: 14px;
        line-height: 24px;
        border-radius: 5px;
        position: absolute;
        width: 307.875px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        background: #fff;
        -webkit-box-shadow: 0px 2px 16px 8px rgba(204,204,204,0.57);
        -moz-box-shadow: 0px 2px 16px 8px rgba(204,204,204,0.57);
        box-shadow: 0px 2px 26px 8px rgba(204,204,204,0.57);;
    }
    .flatpickr-calendar.open,
    .flatpickr-calendar.inline {
        opacity: 1;
        max-height: 640px;
        visibility: visible;
    }
    .flatpickr-calendar.open {
        display: inline-block;
        z-index: 99999;

    }
    .flatpickr-calendar.animate.open {
        -webkit-animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
        animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
    }
    .flatpickr-calendar.inline {
        display: block;
        position: relative;
        top: 2px;
    }
    .flatpickr-calendar.static {
        position: absolute;
        top: calc(100% + 2px);
    }
    .flatpickr-calendar.static.open {
        z-index: 999;
        display: block;
    }
    .flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+1) .flatpickr-day.inRange:nth-child(7n+7) {
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
    }
    .flatpickr-calendar.multiMonth .flatpickr-days .dayContainer:nth-child(n+2) .flatpickr-day.inRange:nth-child(7n+1) {
        -webkit-box-shadow: -2px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;
        box-shadow: -2px 0 0 #e6e6e6, 5px 0 0 #e6e6e6;
    }
    .flatpickr-calendar .hasWeeks .dayContainer,
    .flatpickr-calendar .hasTime .dayContainer {
        border-bottom: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .flatpickr-calendar .hasWeeks .dayContainer {
        border-left: 0;
    }
    .flatpickr-calendar.hasTime .flatpickr-time {
        height: 40px;
        border-top: 1px solid #eee;
    }
    .flatpickr-calendar.noCalendar.hasTime .flatpickr-time {
        height: auto;
    }
    .flatpickr-calendar:before,
    .flatpickr-calendar:after {
        position: absolute;
        display: block;
        pointer-events: none;
        border: solid transparent;
        content: '';
        height: 0;
        width: 0;
        left: 22px;
    }
    .flatpickr-calendar.rightMost:before,
    .flatpickr-calendar.arrowRight:before,
    .flatpickr-calendar.rightMost:after,
    .flatpickr-calendar.arrowRight:after {
        left: auto;
        right: 22px;
    }
    .flatpickr-calendar.arrowCenter:before,
    .flatpickr-calendar.arrowCenter:after {
        left: 50%;
        right: 50%;
    }
    .flatpickr-calendar:before {
        border-width: 5px;
        margin: 0 -5px;
    }
    .flatpickr-calendar:after {
        border-width: 4px;
        margin: 0 -4px;
    }
    .flatpickr-calendar.arrowTop:before,
    .flatpickr-calendar.arrowTop:after {
        bottom: 100%;
    }
    .flatpickr-calendar.arrowTop:before {
        border-bottom-color: #eee;
    }
    .flatpickr-calendar.arrowTop:after {
        border-bottom-color: #fff;
    }
    .flatpickr-calendar.arrowBottom:before,
    .flatpickr-calendar.arrowBottom:after {
        top: 100%;
    }
    .flatpickr-calendar.arrowBottom:before {
        border-top-color: #eee;
    }
    .flatpickr-calendar.arrowBottom:after {
        border-top-color: #fff;
    }
    .flatpickr-calendar:focus {
        outline: 0;
    }
    .flatpickr-wrapper {
        position: relative;
        display: inline-block;
    }
    .flatpickr-months {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
    }
    .flatpickr-months .flatpickr-month {
        background: transparent;
        color: #3c3f40;
        fill: #3c3f40;
        height: 34px;
        line-height: 1;
        text-align: center;
        position: relative;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        overflow: hidden;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        text-decoration: none;
        cursor: pointer;
        position: absolute;
        top: 0;
        height: 34px;
        padding: 10px;
        z-index: 3;
        color: #3c3f40;
        fill: #3c3f40;
    }
    .flatpickr-months .flatpickr-prev-month.flatpickr-disabled,
    .flatpickr-months .flatpickr-next-month.flatpickr-disabled {
        display: none;
    }
    .flatpickr-months .flatpickr-prev-month i,
    .flatpickr-months .flatpickr-next-month i {
        position: relative;
    }
    .flatpickr-months .flatpickr-prev-month.flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month.flatpickr-prev-month {
        /*
              /*rtl:begin:ignore*/
        /*
              */
        left: 0;
        /*
              /*rtl:end:ignore*/
        /*
              */
    }
    /*
          /*rtl:begin:ignore*/
    /*
          /*rtl:end:ignore*/
    .flatpickr-months .flatpickr-prev-month.flatpickr-next-month,
    .flatpickr-months .flatpickr-next-month.flatpickr-next-month {
        /*
              /*rtl:begin:ignore*/
        /*
              */
        right: 0;
        /*
              /*rtl:end:ignore*/
        /*
              */
    }
    /*
          /*rtl:begin:ignore*/
    /*
          /*rtl:end:ignore*/
    .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-months .flatpickr-next-month:hover {
        color: #f64747;
    }
    .flatpickr-months .flatpickr-prev-month:hover svg,
    .flatpickr-months .flatpickr-next-month:hover svg {
        fill: #f64747;
    }
    .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-months .flatpickr-next-month svg {
        width: 14px;
        height: 14px;
    }
    .flatpickr-months .flatpickr-prev-month svg path,
    .flatpickr-months .flatpickr-next-month svg path {
        -webkit-transition: fill 0.1s;
        transition: fill 0.1s;
        fill: inherit;
    }
    .numInputWrapper {
        position: relative;
        height: auto;
    }
    .numInputWrapper input,
    .numInputWrapper span {
        display: inline-block;
    }
    .numInputWrapper input {
        width: 100%;
    }
    .numInputWrapper input::-ms-clear {
        display: none;
    }
    .numInputWrapper input::-webkit-outer-spin-button,
    .numInputWrapper input::-webkit-inner-spin-button {
        margin: 0;
        -webkit-appearance: none;
    }
    .numInputWrapper span {
        position: absolute;
        right: 0;
        width: 14px;
        padding: 0 4px 0 2px;
        height: 50%;
        line-height: 50%;
        opacity: 0;
        cursor: pointer;
        border: 1px solid rgba(64,72,72,0.15);
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .numInputWrapper span:hover {
        background: rgba(0,0,0,0.1);
    }
    .numInputWrapper span:active {
        background: rgba(0,0,0,0.2);
    }
    .numInputWrapper span:after {
        display: block;
        content: "";
        position: absolute;
    }
    .numInputWrapper span.arrowUp {
        top: 0;
        border-bottom: 0;
    }
    .numInputWrapper span.arrowUp:after {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-bottom: 4px solid rgba(64,72,72,0.6);
        top: 26%;
    }
    .numInputWrapper span.arrowDown {
        top: 50%;
    }
    .numInputWrapper span.arrowDown:after {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid rgba(64,72,72,0.6);
        top: 40%;
    }
    .numInputWrapper span svg {
        width: inherit;
        height: auto;
    }
    .numInputWrapper span svg path {
        fill: rgba(60,63,64,0.5);
    }
    .numInputWrapper:hover {
        background: rgba(0,0,0,0.05);
    }
    .numInputWrapper:hover span {
        opacity: 1;
    }
    .flatpickr-current-month {
        font-size: 135%;
        line-height: inherit;
        font-weight: 300;
        color: inherit;
        position: absolute;
        width: 75%;
        left: 12.5%;
        padding: 7.48px 0 0 0;
        line-height: 1;
        height: 34px;
        display: inline-block;
        text-align: center;
        -webkit-transform: translate3d(0px, 0px, 0px);
        transform: translate3d(0px, 0px, 0px);
    }
    .flatpickr-current-month span.cur-month {
        font-family: inherit;
        font-weight: 700;
        color: inherit;
        display: inline-block;
        margin-left: 0.5ch;
        padding: 0;
    }
    .flatpickr-current-month span.cur-month:hover {
        background: rgba(0,0,0,0.05);
    }
    .flatpickr-current-month .numInputWrapper {
        width: 6ch;
        width: 7ch\0;
        display: inline-block;
    }
    .flatpickr-current-month .numInputWrapper span.arrowUp:after {
        border-bottom-color: #3c3f40;
    }
    .flatpickr-current-month .numInputWrapper span.arrowDown:after {
        border-top-color: #3c3f40;
    }
    .flatpickr-current-month input.cur-year {
        background: transparent;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        color: inherit;
        cursor: text;
        padding: 0 0 0 0.5ch;
        margin: 0;
        display: inline-block;
        font-size: inherit;
        font-family: inherit;
        font-weight: 300;
        line-height: inherit;
        height: auto;
        border: 0;
        border-radius: 0;
        vertical-align: initial;
        -webkit-appearance: textfield;
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .flatpickr-current-month input.cur-year:focus {
        outline: 0;
    }
    .flatpickr-current-month input.cur-year[disabled],
    .flatpickr-current-month input.cur-year[disabled]:hover {
        font-size: 100%;
        color: rgba(60,63,64,0.5);
        background: transparent;
        pointer-events: none;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        appearance: menulist;
        background: transparent;
        border: none;
        border-radius: 0;
        box-sizing: border-box;
        color: inherit;
        cursor: pointer;
        font-size: inherit;
        font-family: inherit;
        font-weight: 300;
        height: auto;
        line-height: inherit;
        margin: -1px 0 0 0;
        outline: none;
        padding: 0 0 0 0.5ch;
        position: relative;
        vertical-align: initial;
        -webkit-box-sizing: border-box;
        -webkit-appearance: menulist;
        -moz-appearance: menulist;
        width: auto;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months:focus,
    .flatpickr-current-month .flatpickr-monthDropdown-months:active {
        outline: none;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
        background: rgba(0,0,0,0.05);
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
        background-color: transparent;
        outline: none;
        padding: 0;
    }
    .flatpickr-weekdays {
        background: transparent;
        text-align: center;
        overflow: hidden;
        width: 100%;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        height: 28px;
    }
    .flatpickr-weekdays .flatpickr-weekdaycontainer {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    span.flatpickr-weekday {
        cursor: default;
        font-size: 90%;
        background: transparent;
        color: rgba(0,0,0,0.54);
        line-height: 1;
        margin: 0;
        text-align: center;
        display: block;
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        font-weight: bolder;
    }
    .dayContainer,
    .flatpickr-weeks {
        padding: 1px 0 0 0;
    }
    .flatpickr-days {
        position: relative;
        overflow: hidden;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: start;
        -webkit-align-items: flex-start;
        -ms-flex-align: start;
        align-items: flex-start;
        width: 307.875px;
    }
    .flatpickr-days:focus {
        outline: 0;
    }
    .dayContainer {
        padding: 0;
        outline: 0;
        text-align: left;
        width: 307.875px;
        min-width: 307.875px;
        max-width: 307.875px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        display: inline-block;
        display: -ms-flexbox;
        display: -webkit-box;
        display: -webkit-flex;
        display: flex;
        -webkit-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        -ms-flex-pack: justify;
        -webkit-justify-content: space-around;
        justify-content: space-around;
        -webkit-transform: translate3d(0px, 0px, 0px);
        transform: translate3d(0px, 0px, 0px);
        opacity: 1;
    }
    .dayContainer + .dayContainer {
        -webkit-box-shadow: -1px 0 0 #eee;
        box-shadow: -1px 0 0 #eee;
    }
    .flatpickr-day {
        background: none;
        border: 1px solid transparent;
        border-radius: 150px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        color: #404848;
        cursor: pointer;
        font-weight: 400;
        width: 14.2857143%;
        -webkit-flex-basis: 14.2857143%;
        -ms-flex-preferred-size: 14.2857143%;
        flex-basis: 14.2857143%;
        max-width: 39px;
        height: 39px;
        line-height: 39px;
        margin: 0;
        display: inline-block;
        position: relative;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        text-align: center;
    }
    .flatpickr-day.inRange,
    .flatpickr-day.prevMonthDay.inRange,
    .flatpickr-day.nextMonthDay.inRange,
    .flatpickr-day.today.inRange,
    .flatpickr-day.prevMonthDay.today.inRange,
    .flatpickr-day.nextMonthDay.today.inRange,
    .flatpickr-day:hover,
    .flatpickr-day.prevMonthDay:hover,
    .flatpickr-day.nextMonthDay:hover,
    .flatpickr-day:focus,
    .flatpickr-day.prevMonthDay:focus,
    .flatpickr-day.nextMonthDay:focus {
        cursor: pointer;
        outline: 0;
        background: #e9e9e9;
        border-color: #e9e9e9;
    }
    .flatpickr-day.today {
        border-color: #f64747;
    }
    .flatpickr-day.today:hover,
    .flatpickr-day.today:focus {
        border-color: #f64747;
        background: #f64747;
        color: #fff;
    }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover,
    .flatpickr-day.selected.prevMonthDay,
    .flatpickr-day.startRange.prevMonthDay,
    .flatpickr-day.endRange.prevMonthDay,
    .flatpickr-day.selected.nextMonthDay,
    .flatpickr-day.startRange.nextMonthDay,
    .flatpickr-day.endRange.nextMonthDay {
        background: #4f99ff;
        -webkit-box-shadow: none;
        box-shadow: none;
        color: #fff;
        border-color: #4f99ff;
    }
    .flatpickr-day.selected.startRange,
    .flatpickr-day.startRange.startRange,
    .flatpickr-day.endRange.startRange {
        border-radius: 50px 0 0 50px;
    }
    .flatpickr-day.selected.endRange,
    .flatpickr-day.startRange.endRange,
    .flatpickr-day.endRange.endRange {
        border-radius: 0 50px 50px 0;
    }
    .flatpickr-day.selected.startRange + .endRange:not(:nth-child(7n+1)),
    .flatpickr-day.startRange.startRange + .endRange:not(:nth-child(7n+1)),
    .flatpickr-day.endRange.startRange + .endRange:not(:nth-child(7n+1)) {
        -webkit-box-shadow: -10px 0 0 #4f99ff;
        box-shadow: -10px 0 0 #4f99ff;
    }
    .flatpickr-day.selected.startRange.endRange,
    .flatpickr-day.startRange.startRange.endRange,
    .flatpickr-day.endRange.startRange.endRange {
        border-radius: 50px;
    }
    .flatpickr-day.inRange {
        border-radius: 0;
        -webkit-box-shadow: -5px 0 0 #e9e9e9, 5px 0 0 #e9e9e9;
        box-shadow: -5px 0 0 #e9e9e9, 5px 0 0 #e9e9e9;
    }
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover,
    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay,
    .flatpickr-day.notAllowed,
    .flatpickr-day.notAllowed.prevMonthDay,
    .flatpickr-day.notAllowed.nextMonthDay {
        color: rgba(64,72,72,0.3);
        background: transparent;
        border-color: #e9e9e9;
        cursor: default;
    }
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover {
        cursor: not-allowed;
        color: rgba(64,72,72,0.1);
    }
    .flatpickr-day.week.selected {
        border-radius: 0;
        -webkit-box-shadow: -5px 0 0 #4f99ff, 5px 0 0 #4f99ff;
        box-shadow: -5px 0 0 #4f99ff, 5px 0 0 #4f99ff;
    }
    .flatpickr-day.hidden {
        visibility: hidden;
    }
    .rangeMode .flatpickr-day {
        margin-top: 1px;
    }
    .flatpickr-weekwrapper {
        float: left;
    }
    .flatpickr-weekwrapper .flatpickr-weeks {
        padding: 0 12px;
        -webkit-box-shadow: 1px 0 0 #eee;
        box-shadow: 1px 0 0 #eee;
    }
    .flatpickr-weekwrapper .flatpickr-weekday {
        float: none;
        width: 100%;
        line-height: 28px;
    }
    .flatpickr-weekwrapper span.flatpickr-day,
    .flatpickr-weekwrapper span.flatpickr-day:hover {
        display: block;
        width: 100%;
        max-width: none;
        color: rgba(64,72,72,0.3);
        background: transparent;
        cursor: default;
        border: none;
    }
    .flatpickr-innerContainer {
        display: block;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        overflow: hidden;
    }
    .flatpickr-rContainer {
        display: inline-block;
        padding: 0;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .flatpickr-time {
        text-align: center;
        outline: 0;
        display: block;
        height: 0;
        line-height: 40px;
        max-height: 40px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        overflow: hidden;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
    }
    .flatpickr-time:after {
        content: "";
        display: table;
        clear: both;
    }
    .flatpickr-time .numInputWrapper {
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        width: 40%;
        height: 40px;
        float: left;
    }
    .flatpickr-time .numInputWrapper span.arrowUp:after {
        border-bottom-color: #404848;
    }
    .flatpickr-time .numInputWrapper span.arrowDown:after {
        border-top-color: #404848;
    }
    .flatpickr-time.hasSeconds .numInputWrapper {
        width: 26%;
    }
    .flatpickr-time.time24hr .numInputWrapper {
        width: 49%;
    }
    .flatpickr-time input {
        background: transparent;
        -webkit-box-shadow: none;
        box-shadow: none;
        border: 0;
        border-radius: 0;
        text-align: center;
        margin: 0;
        padding: 0;
        height: inherit;
        line-height: inherit;
        color: #404848;
        font-size: 14px;
        position: relative;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-appearance: textfield;
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .flatpickr-time input.flatpickr-hour {
        font-weight: bold;
    }
    .flatpickr-time input.flatpickr-minute,
    .flatpickr-time input.flatpickr-second {
        font-weight: 400;
    }
    .flatpickr-time input:focus {
        outline: 0;
        border: 0;
    }
    .flatpickr-time .flatpickr-time-separator,
    .flatpickr-time .flatpickr-am-pm {
        height: inherit;
        float: left;
        line-height: inherit;
        color: #404848;
        font-weight: bold;
        width: 2%;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        -webkit-align-self: center;
        -ms-flex-item-align: center;
        align-self: center;
    }
    .flatpickr-time .flatpickr-am-pm {
        outline: 0;
        width: 18%;
        cursor: pointer;
        text-align: center;
        font-weight: 400;
    }
    .flatpickr-time input:hover,
    .flatpickr-time .flatpickr-am-pm:hover,
    .flatpickr-time input:focus,
    .flatpickr-time .flatpickr-am-pm:focus {
        background: #f1f1f1;
    }
    .flatpickr-input[readonly] {
        cursor: pointer;
    }
    @-webkit-keyframes fpFadeInDown {
        from {
            opacity: 0;
            -webkit-transform: translate3d(0, -20px, 0);
            transform: translate3d(0, -20px, 0);
        }
        to {
            opacity: 1;
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }
    }
    @keyframes fpFadeInDown {
        from {
            opacity: 0;
            -webkit-transform: translate3d(0, -20px, 0);
            transform: translate3d(0, -20px, 0);
        }
        to {
            opacity: 1;
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }
    }
    .flatpickr-calendar {
        width: 307.875px;
    }
    .dayContainer {
        padding: 0;
        border-right: 0;
    }
    span.flatpickr-day,
    span.flatpickr-day.prevMonthDay,
    span.flatpickr-day.nextMonthDay {
        border-radius: 0 !important;
        border: 1px solid #e9e9e9;
        max-width: none;
        border-right-color: transparent;
    }
    span.flatpickr-day:nth-child(n+8),
    span.flatpickr-day.prevMonthDay:nth-child(n+8),
    span.flatpickr-day.nextMonthDay:nth-child(n+8) {

    }
    span.flatpickr-day:nth-child(7n-6),
    span.flatpickr-day.prevMonthDay:nth-child(7n-6),
    span.flatpickr-day.nextMonthDay:nth-child(7n-6) {
        border-left: 0;
    }
    span.flatpickr-day:nth-child(n+36),
    span.flatpickr-day.prevMonthDay:nth-child(n+36),
    span.flatpickr-day.nextMonthDay:nth-child(n+36) {
        border-bottom: 0;
    }
    span.flatpickr-day:nth-child(-n+7),
    span.flatpickr-day.prevMonthDay:nth-child(-n+7),
    span.flatpickr-day.nextMonthDay:nth-child(-n+7) {
        margin-top: 0;
    }
    span.flatpickr-day.today:not(.selected),
    span.flatpickr-day.prevMonthDay.today:not(.selected),
    span.flatpickr-day.nextMonthDay.today:not(.selected) {
        border-color: #e9e9e9;
        border-right-color: transparent;
        border-top-color: transparent;
        border-bottom-color: #f64747;
    }
    span.flatpickr-day.today:not(.selected):hover,
    span.flatpickr-day.prevMonthDay.today:not(.selected):hover,
    span.flatpickr-day.nextMonthDay.today:not(.selected):hover {
        border: 1px solid #f64747;
    }
    span.flatpickr-day.startRange,
    span.flatpickr-day.prevMonthDay.startRange,
    span.flatpickr-day.nextMonthDay.startRange,
    span.flatpickr-day.endRange,
    span.flatpickr-day.prevMonthDay.endRange,
    span.flatpickr-day.nextMonthDay.endRange {
        border-color: #4f99ff;
    }
    span.flatpickr-day.today,
    span.flatpickr-day.prevMonthDay.today,
    span.flatpickr-day.nextMonthDay.today,
    span.flatpickr-day.selected,
    span.flatpickr-day.prevMonthDay.selected,
    span.flatpickr-day.nextMonthDay.selected {
        z-index: 2;
    }
    .rangeMode .flatpickr-day {
        margin-top: -1px;
    }
    .flatpickr-weekwrapper .flatpickr-weeks {
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .flatpickr-weekwrapper span.flatpickr-day {
        border: 0;
        margin: -1px 0 0 -1px;
    }
    .hasWeeks .flatpickr-days {
        border-right: 0;
    }

    @media screen and (min-width:0\0) and (min-resolution: +72dpi) {
        span.flatpickr-day {
            display: block;
            -webkit-box-flex: 1;
            -webkit-flex: 1 0 auto;
            -ms-flex: 1 0 auto;
            flex: 1 0 auto;
        }
    }

</style>
<script src="/js/main.js"></script>
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

</body>

</html>
