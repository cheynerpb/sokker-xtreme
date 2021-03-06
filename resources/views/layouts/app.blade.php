<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @guest
            <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm shadow-sm">
        @else
            <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm shadow-sm">
        @endguest
            <div class="container">

                    @guest
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Xtreme Doping
                    @else
                    <a class="navbar-brand" href="{{ route('home') }}">
                        {{Auth::user()->team->team_name}}
                        <span class="badge badge-danger">Ranking: {{Auth::user()->team->rank}}</span>
                    @endguest

                    </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    <ul class="navbar-nav mr-auto">
                        @if (\Auth::guard('system_users')->check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('insert_player') }}">Insertar</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('show_ranking') }}">Ranking</a>
                        </li>
                        @if (\Auth::guard('system_users')->check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('editions.index') }}">Ediciones</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('system_users.index') }}">Usuarios</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reference_table') }}">Tabla Referencial</a>
                        </li>
                    </ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if (!\Auth::guard('system_users')->check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('system.login.form') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('system.register.form') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::guard('system_users')->user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('system.logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src=""> -->
        <!-- <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script> -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript" language="javascript" src="js/html2canvas.min.js"></script>
        <main class="py-4">
            @yield('content')
        </main>
        @stack('scripts')
    </div>

</body>
</html>
