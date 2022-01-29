<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>

            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            @if (Route::has('system.login.form'))
                <div class="top-right links">

                    @if(\Auth::guard('system_users')->check())
                        {{-- <a href="{{ route('show_ranking') }}">Ranking</a> --}}
                        <a class="dropdown-item" href="{{ route('system.logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('system.login.form') }}">Login</a>

                        @if (Route::has('system.register.form'))
                            <a href="{{ route('system.register.form') }}">Register</a>
                        @endif
                    @endif
                </div>
            @endif

            <div class="content">
                <div>
                    <img src="{{ asset('assets/images/nuevo-logo.jpg') }}" class="p-3">
                </div>
                <div class="title m-b-md">
                    Xtreme Doping
                </div>

                <div class="links">

                    <a href="{{route('show_ranking')}}">Tabla</a>
                    @if (\Auth::guard('system_users')->check())
                        <a href="{{route('insert_player')}}">Insertar</a>
                        <a href="{{route('show_form')}}">Actualizar</a>
                    @endif

                </div>
            </div>
        </div>
    </body>
</html>
