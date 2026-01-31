<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- SEO Meta -->
    <meta name="description" content="Boost your social media presence with {{ config('app.name', 'Booster') }}. We offer premium, fast, and reliable social media growth services for Instagram, Facebook, TikTok, Twitter, and more." />
    <meta name="keyword" content="social media growth, buy followers Nigeria, Instagram growth services, TikTok followers, Facebook likes, Twitter engagement, social media marketing Nigeria" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->

    <!--! BEGIN: Apps Title-->
    <title>{{ config('app.name', 'Booster') }} || @yield('title', 'Premium Social Media Growth Services')</title>
    <!--! END:  Apps Title-->

    <!-- Open Graph / Social Sharing -->
    <meta property="og:title" content="{{ config('app.name', 'Booster') }} – Premium Social Media Growth Services" />
    <meta property="og:description" content="Grow your Instagram, TikTok, Facebook, and Twitter accounts with fast, secure, and reliable social media growth services." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('assets/images/social-preview.png') }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Booster') }}" />

    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/B.png') }}" />
    <!--! END: Favicon-->

    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <!--! END: Bootstrap CSS-->

    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/daterangepicker.min.css') }}" />
    <!--! END: Vendors CSS-->

    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}" />
    <!--! END: Custom CSS-->

    @stack('styles')
</head>
<body>
