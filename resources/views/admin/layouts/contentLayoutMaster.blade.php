{{--@isset($pageConfigs)
{{updatePageConfig($pageConfigs)}}
@endisset--}}

    <!DOCTYPE html>
@php
    $configData = applClasses();
@endphp
<html class="loading {{ $configData['theme'] === 'light' ? '' : $configData['layoutTheme'] }}"
      lang="vi"
      data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}"
      @if ($configData['theme'] === 'dark') data-layout="dark-layout" @endif>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
          content="Dự án ProSell">
    <meta name="keywords"
          content="admin prosell, prosell, web app">
    <meta name="author" content="tuanna">
    <title>ZapiCare - @yield('title')</title>
    {{--<link rel="apple-touch-icon" href="{{ asset('images/ico/favicon-32x32.png') }}">--}}
    {{--<link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo-bellhome.ico') }}">--}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
          rel="stylesheet">

    {{-- Include core + vendor Styles --}}
    @include('admin/panels/styles')
    <style>
        .navbar-header.expanded .brand-logo img{
            width: 100px !important;
            margin-left: 45px;
            max-width: none !important;
        }
    </style>
@yield('style')
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
@isset($configData['mainLayoutType'])
    @extends((( $configData["mainLayoutType"] === 'horizontal') ? 'admin.layouts.horizontalLayoutMaster' :
    'admin.layouts.verticalLayoutMaster' ))
@endisset
