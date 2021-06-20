@extends('layouts.app')

@section('content')
{{-- Estilo CSS --}}

{{-- background --}}
<img id="background" src="{{asset('img/mountains.jpg')}}" style="">

<div class="container">
    <div class="row justify-content-center">



        <div id="conteudo-login" class="col-sm-7">
            <div class="row">
                <div id="esquerda-login" class="col-sm-7">
                    <div class="row justify-content-center">
                        <img id="logo-login" src="{{asset('img/logo.svg')}}" style="">
                    </div>
                </div>
                <div id="direita-login" class="col-sm-5">

                    <div class="row justify-content-center">
                        <div id="titulo-login">Login</div>
                    </div>


                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row justify-content-center">

                            <div class="col-md-10">
                                <label for="email" class="col-form-label text-md-right">{{ __('E-Mail') }}</label>
                                <input id="email" type="email" class="input-ludke form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row justify-content-center">
                            <div class="col-md-10">
                                <label for="password" class="col-form-label text-md-right">{{ __('Senha') }}</label>
                                <input id="password" type="password" class="input-ludke form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row justify-content-center">
                            <div class="col-md-10">
                                <button type="submit" name = "button" id="button" class="btn btn-primary-ludke" onclick="ativo(1)" style="margin-top:20px">
                                    {{ __('Login') }}
                                </button>

                                {{-- @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>



        {{-- <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@section('javascript')
    <script>
        function LoginUser(t) {

            $("#background").fadeIn(800);

            // animação Fade
            $("#conteudo-login").delay(800).fadeIn(1200);

            //var button =  $("submit[name=button]").val();
            var token    = $("input[name=_token]").val();
            var email = $("input[name=email]").val();
            var password  = $("input[name=password]").val();

            console.log(token);
            if(button == 1)
                var data = {
                    _token:token,
                    email:email,
                    password:password
                };

                $.ajax({
                    type:'post',
                    url:'/login',
                    data: data,
                    cache:false,
                    success: function (data) {
                        console.log("Request valido")

                    },
                    error: function (data) {


                        //Fazer Verificação
                       //alert('Fail to run login...');



                    }
                });




        }

        $(function(){


            var t = LoginUser();
            //animação top-meio
            // $("#conteudo-login").delay("slow").show(1000).fadeIn(1000);

        });

        function ativo(id) {
            console.log(id);

        }

        // document.getElementById("background").style.display = "block";
        // document.getElementById("conteudo-login").style.display = "block";
    </script>
@endsection
