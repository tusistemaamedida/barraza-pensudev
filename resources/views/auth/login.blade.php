<!DOCTYPE html>
<html lang="es" class="light">

    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.png" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="sistemasdev.com">
        <title>Pensudev</title>

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon1.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon2.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon3.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="{{asset('dist/css/app.css')}}" />

    </head>

    <body class="login">
        <div class="container sm:px-10">
            <form method="POST" action="{{ route('login') }}" style="">
                @csrf
                <div class="block xl:grid grid-cols-2 gap-4">
                    <div class="hidden xl:flex flex-col min-h-screen">
                        <a href="" class="-intro-x flex items-center pt-5">
                            <img alt="Pensudev" class="w-6" src="{{asset('dist/images/logo.png')}}">
                            <span class="text-white text-lg ml-3"> Pensudev</span>
                        </a>
                        <div class="my-auto">
                            <img alt="Pensudev" class="-intro-x w-1/2 -mt-16" src="{{asset('dist/images/illustration.svg')}}">
                            <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                                Ingresa a tu cuenta
                                <br>
                                con los datos solicitados.
                            </div>
                            <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Gestión y Control en Cámaras</div>
                        </div>
                    </div>

                    <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                        <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                            <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                                Ingresar
                            </h2>
                            <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">Ingresa a tu cuenta con los datos solicitados.. Gestión y Control en Cámaras</div>
                            <div class="intro-x mt-8">
                                <input id="email" type="email"
                                placeholder="Ingresa tu Email"
                                class="intro-x login__input form-control py-3 px-4 block @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <input id="password" type="password" placeholder="Ingresa tu Password"
                                 class="intro-x login__input form-control py-3 px-4 block mt-4 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                                <div class="flex items-center mr-auto">
                                    <input id="remember-me" type="checkbox" class="form-check-input border mr-2">
                                    <label class="cursor-pointer select-none" for="remember-me" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>Recordarme.</label>
                                </div>
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Ingresar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script src="{{asset('dist/js/app.js')}}"></script>

    </body>
</html>
