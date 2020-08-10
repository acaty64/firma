<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="http://www.ucss.edu.pe" target="_blank">
          <img class="navbar-brand" src="{{asset('images/logo-ucss.png')}}" ></img>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
          {{-- Left Side Of Navbar --}}
            @if(Auth::user()->id == 1)
              <a href="{{ route('access.index') }}" class="btn btn-primary mr-2" role="button">Accesos</a>
              <a href="{{ route('sign') }}" class="btn btn-primary" role="button">Firmas</a>
            @endif
          </ul>
        </div>
        <ul class="navbar-nav ml-auto">
          {{-- Right Side Of Navbar --}}
          @if( config('app.debug') )
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">
                {{ __('Login') }}
              </a>
            </li>
          @endif
          @auth
                {{ Auth::user()->name }}
          @endif
        </ul>
      </div>
    </nav>

    @include('layouts.partials.errors')
    <main class="py-4">

      @yield('content')
    </main>
    <!-- Footer -->
    @include('layouts.partials.footer')
  </div>
  @yield('script')
</body>
</html>
