<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .logout-button-container {
                position: absolute;
                top: 10px;
                right: 10px;
            }
            .membercenter-container{
                position: absolute;
                top: 10px;
                left: 0px;
            }
            .message{
                white-space: pre-wrap;
            }
        </style>
        <title>@yield('title')</title>
    </head>
    <body>
        <div>
            @if(Auth::user())
                <div class="membercenter-container">
                    <a href="{{ route('membercenter.index') }}">會員中心</a><br>
                </div>
                <div class="logout-button-container">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">登出</button>
                    </form>
                </div>
            @endif
        </div>

        <div>
            @yield('content')
        </div>

    </body>
</html>