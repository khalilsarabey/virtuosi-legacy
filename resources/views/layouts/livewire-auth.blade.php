<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $site_settings->name }} Login</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/default.css">
    <x-brand-colors />
    @livewireStyles
    <style>
        input:focus, .form-control:focus {
            box-shadow: none;
            border-color:  {{ $site_settings->primary_color ?? '#000000' }};
        }
        .btn:focus, button:focus {
            outline: 0 !important;
            box-shadow: none !important;
            color: white !important;
            border-color: inherit !important;
        }
        .btn:active, button:active {
            outline: 0 !important;
            box-shadow: none !important;
            color: white !important;
            border-color: inherit !important;
        }
        .btn:visited, button:visited {
            outline: 0 !important;
            box-shadow: none !important;
            color: white !important;
            border-color: inherit !important;
        }
    </style>
</head>
<body class="vh-100" style="background-image: url('{{ $site_settings->login_bg->first() ? $site_settings->login_bg->first()['url'] : '' }}'); background-repeat: no-repeat; background-size: cover; background-position: center center">
{{ $slot }}
@livewireScripts
</body>
</html>
