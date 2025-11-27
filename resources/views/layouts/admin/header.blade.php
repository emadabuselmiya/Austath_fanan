<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="ProMax">
<meta name="keywords" content="ProMax">
<meta name="author" content="Store">

@php($website_name=get_business_settings('business_name', false))
@php($logo=get_business_settings('logo', false))
@php($fav_icon=get_business_settings('fav_icon', false))

<!-- PWA  -->
<meta name="theme-color" content="#6777ef"/>
<link rel="apple-touch-icon" href="{{ $logo ? asset('storage/' . $logo) : asset('app-assets/logo2.png') }}">
{{--<link rel="manifest" href="{{ asset('/manifest.json') }}">--}}

<meta property="og:locale" content="en_US"/>
<meta property="og:type" content="Store"/>
<meta property="og:title" content="ProMax"/>
<meta property="og:url" content="{{env('APP_URL')}}"/>
<meta property="og:site_name" content="ProMax"/>
<meta property="og:image" content="{{ $logo ? asset('storage/' . $logo) : asset('app-assets/logo2.png') }}"/>

<meta name="csrf-token" content="{{ csrf_token() }}">


<title>@yield('title', '') - أستاذ فنان</title>
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
      rel="stylesheet">

@include('layouts.admin.css')





