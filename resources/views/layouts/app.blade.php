<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

   
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/styleLoginCadastro.css')}}">

    <!-- Scripts -->
    
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{ asset('js/pagination.min.js')}}"></script>
    
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
    -->
        <script src="{{asset('js/jquery.maskMoney.min.js')}}"></script>

   
</head>
<body>
    <div id="app">
        
        @if(Auth::check())
            @component('component.navbar')
            @endcomponent
        @endif
        <main class="">
            
            @yield('content')
        </main>
    </div>

    @hasSection ('javascript')
        @yield('javascript')
    @else
        
    @endif



     
     <!-- Fonts -->
     <link rel="dns-prefetch" href="//fonts.gstatic.com">
     <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
 
</body>
</html>
