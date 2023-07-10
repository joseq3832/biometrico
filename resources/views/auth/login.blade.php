<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema de control de asistensias</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('dist/css/skins/skin-red.min.css')}}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body style="background-color:#BDC3C7;">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light  shadow-sm" style="background-color:#278747;">
            <div class="container" >
                <a class="navbar-brand text-light" href="{{ url('/') }}" style="background-color:#278747;">
                Sistema de control de sssasistensias
                </a>
                

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right" >
                    @auth
                        <a href="{{ url('/home') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500" style="color:#FF0000;">Home</a>
                    @else
                        

                    @endauth
                </div>
            
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-1">
        <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
        <br><br>
                
            <div class="card">
                
                <div class="card-header text-center">{{ __('LOGIN') }}</div>

                <div class="card-body">
                    <br>
                    
                    

                <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex text-center">
                    <img src="../images/cLogoS.png" alt="">
                    </div>
                </div>
                <br>
                <br>
                    <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    @if (Route::has('login'))

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Cédula') }}</label>

                            <div class="col-md-6">
                                <input id="cedpersona" type="text" class="form-control" name="cedpersona" value="{{ old('cedpersona') }}" required autocomplete="cedula" autofocus>

                                @error('cedpersona')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="clave" class="col-md-4 col-form-label text-md-end">{{ __('Contaseña') }}</label>

                            <div class="col-md-6">
                            <input type="password" placeholder="Contraseña..." name="password" class="form-control" id="password"/>
                            </div>
                            {!! $errors-> first('password', '<span class="help-block">:message</span> ') !!}
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
             
                                </div>
                                

                            </div>
                            

                        </div>

                        <div class="row mb-0">
                            <div class="col-md-5 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Login') }}
                                </button>

                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
</div>
</body>

</html>

