<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ URL::asset('assets/images/favicon.ico') }}" type="image/x-icon"/>

    <title>Login</title>

    {{--bootstrap--}}
    <link href="{{asset('assets/css/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    {{--custom style--}}
    <link href="{{asset('assets/css/login/style.css')}}" rel="stylesheet">
</head>
<body>
<div id="app">

    <section id="wrapper" class="new-login-register">
        <div class="lg-info-panel">
            <div class="inner-panel">
                <div class="lg-content">
                    <h2>Laravel Admin Panel With Rest Api</h2>
                </div>
            </div>
        </div>

        <main class="py-4">
            @yield('content')
        </main>

    </section>

</div>
</body>
<!-- jQuery -->
<script src="{{asset('assets/js/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{asset('assets/css/bootstrap/dist/js/bootstrap.min.js')}}"></script>
</html>
