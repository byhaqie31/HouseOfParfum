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

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/checkout/">
    <!-- Scripts -->
    
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        a#pills-men-tab.nav-link.active,
        a#pills-women-tab.nav-link.active,
        a#pills-gift-tab.nav-link.active,
        a#pills-unisex-tab.nav-link.active,
        a#pills-edp-tab.nav-link.active,
        a#pills-edt-tab.nav-link.active,
        a#pills-edc-tab.nav-link.active
        a#pills-autumn-tab.nav-link.active,
        a#pills-winter-tab.nav-link.active,
        a#pills-spring-tab.nav-link.active,
        a#pills-summer-tab.nav-link.active{
            background-color: #dc3545;
            color: white;
        
        }

        a#pills-men-tab.nav-link,
        a#pills-women-tab.nav-link,
        a#pills-gift-tab.nav-link,
        a#pills-unisex-tab.nav-link,
        a#pills-edt-tab.nav-link,
        a#pills-edp-tab.nav-link,
        a#pills-edc-tab.nav-link,
        a#pills-autumn-tab.nav-link,
        a#pills-winter-tab.nav-link,
        a#pills-spring-tab.nav-link,
        a#pills-summer-tab.nav-link{
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

        .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .container {
        max-width: 960px;
      }
    </style>
</head>

<body>
    @include('inc.navbar')
    @include('inc.messages')
    <div class="container  shadow-sm mt-0 bg-white rounded" >
            @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script>
    (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
        })()

    </script>
    

</body>
</html>
