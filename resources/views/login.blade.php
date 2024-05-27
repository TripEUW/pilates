<!-- begin:: Page -->
@extends('layouts.app')
@section('content')
<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor background-login">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container">
                    <div class="kt-login__logo">
                        <a href="#">
                            <img src="{{ asset('assets') }}/images/logo-dashboard.png" width="180">
                        </a>
                    </div>
                    @include("$theme/parts/alerts")


                    <div class="kt-login__signin">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">Ingresar a tu cuenta</h3>
                        </div>
                        <form class="kt-form" action="{{ route('login_in') }}" method="POST" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Email"
                                    value="{{ old('resetForm') ? '' : old('email') }}" name="email" autocomplete="off">
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="password" placeholder="Contraseña" name="password">
                            </div>
                            <div class="row kt-login__extra">

                                <div class="col kt-align-center">
                                    @if (Route::has('employee.password.request'))
                                        <a href="{{ route('employee.password.request') }}" id="kt_login_forgot"
                                            class="kt-login__link">Olvidó su contraseña ?</a>
                                    @endif
                                </div>
                            </div>
                            <div class="kt-login__actions">
                                <button id="kt_login_signin_submit"
                                    class="btn btn-brand btn-pill kt-login__btn-primary">Acceder</button>
                            </div>
                        </form>
                    </div>





                    <div class="kt-login__forgot">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">Olvidó su contraseña</h3>
                            <div class="kt-login__desc">Ingrese su correo electrónico para restablecer su contraseña:
                            </div>
                        </div>
                        <form class="kt-form" action="{{ route('employee.password.email') }}" method="POST">
                            @csrf
                            @method('post')
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Email" name="email"
                                    id="kt_email" value="{{ old('resetForm') ? old('email') : '' }}" autocomplete="off"
                                    required autofocus>
                            </div>
                            <input type="hidden" value="true" name="resetForm">
                            <script>
                                var resetForm = @json(old('resetForm'));
                            </script>

                            <div class="kt-login__actions">
                                <button id="kt_login_forgot_submit"
                                    class="btn btn-brand btn-pill kt-login__btn-primary">Enviar</button>&nbsp;&nbsp;
                                <button id="kt_login_forgot_cancel"
                                    class="btn btn-secondary btn-pill kt-login__btn-secondary">Cancelar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
