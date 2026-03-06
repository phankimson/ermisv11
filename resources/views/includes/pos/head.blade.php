<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS Ermis')</title>
    <link rel="icon" type="image/png" href="{{ asset('addon/img/icon.png') }}" sizes="32x32">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#effaf7',
                            100: '#d5f1ea',
                            500: '#0e9279',
                            600: '#0a7d67',
                            700: '#085f4f'
                        }
                    }
                }
            }
        };
    </script>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('addon/css/pos/style.css') }}">
</head>
<body class="pos-body">

