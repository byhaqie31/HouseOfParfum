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
    </style>
</head>
<script>
    $('.toast').toast({})
</script>
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
    @if (Session::has('role'))
    <a class="btn btn-warning px-4 chat-btn rounded-pill " href ="/chat">
        <i class="fa-brands fa-rocketchat"></i>
        <span>Live Chat</span>
    </a>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>

</html>
