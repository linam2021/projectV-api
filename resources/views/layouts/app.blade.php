<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Project V</title>
    <link rel="shortcut icon" href="{{asset('img/gameIcon.png')}}" type="image/x-icon">
	<link rel="icon" href="{{asset('img/gameIcon.jpg')}}" type="image/x-icon">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet' type='text/css'>

    <!-- Stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/bootstrap.rtl.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/all.min.css')}}">

    <style>
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
      </style>
</head>
<body>
    <div id="app" class="container" >
        <nav class="navbar navbar-expand-md shadow-sm navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#"><img src="{{asset('img/gameLogoWhite.png')}}" alt="The Game logo">
                    Project V
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            {{-- @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">إنشاء حساب</a>
                                </li>
                            @endif --}}
                        @else
                        <li class="nav-item dropdown ml-auto ">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->email }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-start" aria-labelledby="navbarDarkDropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                 تسجيل الخروج
                               </a></li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                          </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')     
    </div>
    <!-- Footer -->
    <div class="container">
        <footer class="bg-dark text-center text-white bg-dark">
            <!-- Copyright -->
            <div class="text-center p-3">
                <span>Copyright &copy; TG Developers 2021</span>
            </div>
            <!-- Copyright -->
        </footer>
    <!-- Footer -->
    </div>
    <!-- SCRIPTS -->
    <script src="{{URL::asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{URL::asset('js/all.min.js')}}"></script>
</body>
</html>
