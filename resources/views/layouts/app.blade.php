<!DOCTYPE html>
<html lang="es" class="light">
    @include('partials.head')
    <body class="py-5 md:py-0">
        @include('partials.mobile-menu')
        @include('partials.top-bar1')
        @include('partials.top-nav')

        <div class="content content--top-nav">
            <div id="spinner">
                <img src="{{asset('spinner.gif')}}"/>
                <p style="color: white"> Cargando...</p>
            </div>
            @yield('content')
        </div>
        <script src="{{asset('dist/js/app.js')}}"></script>
        <script type="text/javascript" src="{{asset('toastify/toastify-js.js')}}"></script>
        @yield('js')
        <script src="{{asset('dist/js/scripts.js')}}"></script>
    </body>
</html>
