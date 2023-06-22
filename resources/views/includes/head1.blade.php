<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset="UTF-8">
 <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <!-- Remove Tap Highlight on Windows Phone IE -->
 <meta name="msapplication-tap-highlight" content="no"/>

 <link rel="icon" type="image/png" href="{{ asset('addon/img/icon.png') }}" sizes="16x16">
 <link rel="icon" type="image/png" href="{{ asset('addon/img/icon.png') }}" sizes="32x32">

 <title>@yield('title')</title>
 @stack('css_up')


 <!-- uikit -->
 <link rel="stylesheet" href="{{ asset('library/uikit/css/uikit.almost-flat.min.css')}}" media="all">

 <!-- flag icons -->
 <link rel="stylesheet" href="{{ asset('assets/icons/flags/flags.min.css')}}" media="all">

 <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.default-v2.min.css') }}" />
 <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.default.mobile.min.css') }}" />
 <link rel="stylesheet" href="{{ asset('addon/css/customize2.css') }}" />
 <link rel="stylesheet" href="{{ asset('addon/css/customize1.css') }}" />
 <!-- altair admin -->
 <link rel="stylesheet" href="{{ asset('assets/css/main.min.css')}}" media="all">

<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="locale" content="{{ app()->getLocale() }}"/>
<meta name="api_token" content="{{ Auth::user()->api_token }}"/>

 <!-- matchMedia polyfill for testing media queries in JS -->
 <!--[if lte IE 9]>
     <script type="text/javascript" src="bower_components/matchMedia/matchMedia.js"></script>
     <script type="text/javascript" src="bower_components/matchMedia/matchMedia.addListener.js"></script>
 <![endif]-->
@stack('css_down')

</head>
