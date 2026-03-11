<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>House of Parfum</title>
    {{-- <title>{{ config('app.name', 'Zpeed Auto-HR') }}</title> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    
    <!-- Scripts -->
    
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        a#pills-men-tab.nav-link.active, a#pills-women-tab.nav-link.active, a#pills-gift-tab.nav-link.active,
        a#pills-unisex-tab.nav-link.active, a#pills-edp-tab.nav-link.active, a#pills-edt-tab.nav-link.active,
        a#pills-edc-tab.nav-link.active, a#pills-autumn-tab.nav-link.active, a#pills-winter-tab.nav-link.active,
        a#pills-spring-tab.nav-link.active, a#pills-summer-tab.nav-link.active, a#pills-pending-tab.nav-link.active,
        a#pills-done-tab.nav-link.active, a#pills-delivery-tab.nav-link.active,  a#pills-parfum-tab.nav-link.active{
            background-color: #dc3545;
            color: white;
        
        }

        a#pills-men-tab.nav-link, a#pills-women-tab.nav-link, a#pills-gift-tab.nav-link, a#pills-unisex-tab.nav-link,
        a#pills-edt-tab.nav-link, a#pills-edp-tab.nav-link, a#pills-edc-tab.nav-link, a#pills-autumn-tab.nav-link,
        a#pills-winter-tab.nav-link, a#pills-spring-tab.nav-link, a#pills-summer-tab.nav-link, a#pills-pending-tab.nav-link, 
        a#pills-done-tab.nav-link, a#pills-delivery-tab.nav-link,a#pills-done-tab.nav-link, a#pills-parfum-tab.nav-link{
            color: #777;
        }
        .add-perfume {
            /* display: none; */

            position: fixed;
            bottom: 20px;
            right: 30px;
            z-index: 99;
            font-size: 18px;
            cursor: pointer;
            padding: 15px;
        }

        .chat-btn {
            /* display: none; */
            font-weight: bold;
            position: fixed;
            bottom: 20px;
            right: 1700px;
            z-index: 99;
            font-size: 18px;
            cursor: pointer;
            padding: 15px;
        }

        .bg-main{
            background-color: #eeeeee;
        }

        .status-width {
            width: 150px !important;
        }


        .btn-primary-outline {
        background-color: #eeeeee;
        /* border-color: #ccc; */
        }

        .btn-primary-outline:hover {
        background-color:#cfcfcf;
        /* border-color: #ccc; */
        }

        .ctc-icon{
            width: 4em !important;
            height: 4em !important;

        }
        
        span {cursor:pointer; }


		.qty{
			height:34px;
            width: 30px;
            text-align: center;
            font-size: 15px;
			border:1px solid #ddd;
			border-radius:4px;
            display: inline-block;
            vertical-align: middle;
        }


        .chat-box {
            height: 100%;
            width: 100%;
            background-color: #fff;
            overflow: auto;
            
        }

        .chats {
            padding: 0px 15px
        }

        .chat-avatar {
            float: right
        }

        .chat-avatar .avatar {
            width: 30px
                -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,0.2),0 6px 10px 0 rgba(0,0,0,0.3);
            box-shadow: 0 2px 2px 0 rgba(0,0,0,0.2),0 6px 10px 0 rgba(0,0,0,0.3);
        }

        .chat-body {
            display: block;
            margin: 10px 30px 0 0;
            overflow: hidden
        }

        .chat-body:first-child {
            margin-top: 0
        }

        .chat-content {
            position: relative;
            display: block;
            float: right;
            padding: 8px 15px;
            margin: 0 20px 10px 0;
            clear: both;
            color: #fff;
            background-color: #62a8ea;
            border-radius: 4px;
                -webkit-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.37);
            box-shadow: 0 1px 4px 0 rgba(0,0,0,0.37);
        }

        .chat-content:before {
            position: absolute;
            top: 10px;
            right: -10px;
            width: 0;
            height: 0;
            content: '';
            border: 5px solid transparent;
            border-left-color: #62a8ea
        }

        .chat-content>p:last-child {
            margin-bottom: 0
        }

        .chat-content+.chat-content:before {
            border-color: transparent
        }

        .chat-time {
            display: block;
            margin-top: 8px;
            color: rgba(255, 255, 255, .6)
        }

        .chat-left .chat-avatar {
            float: left
        }

        .chat-left .chat-body {
            margin-right: 0;
            margin-left: 30px
        }

        .chat-left .chat-content {
            float: left;
            margin: 0 0 10px 20px;
            color: #76838f;
            background-color: #dfe9ef
        }

        .chat-left .chat-content:before {
            right: auto;
            left: -10px;
            border-right-color: #dfe9ef;
            border-left-color: transparent
        }

        .chat-left .chat-content+.chat-content:before {
            border-color: transparent
        }

        .chat-left .chat-time {
            color: #a3afb7
        }


        .avatar img {
            width: 100%;
            max-width: 100%;
            height: auto;
            border: 0 none;
            border-radius: 1000px;
        }
        .chat-avatar .avatar {
            width: 30px;
        }
        .avatar {
            position: relative;
            display: inline-block;
            width: 40px;
            white-space: nowrap;
            border-radius: 1000px;
            vertical-align: bottom;
        }

    </style>

</head>

<body>
    @include('inc.navbar')
    @include('inc.messages')
    @yield('content')


    {{-- <div class="container">
        <div class="container-fluid"><br>
            @include('inc.messages')
            @yield('content')
        </div>
    </div> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</body>

</html>
