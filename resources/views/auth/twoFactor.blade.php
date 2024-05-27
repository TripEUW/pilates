@extends('layouts.app')
@section('content')
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor background-login" style="min-height:100vh">
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v4 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor background-login">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__logo">
                            <!-- Logo content here if needed -->
                        </div>
                        <div class="col-md-8">
                            <div class="card-group" style="min-width:400px; min-height:400px; display:flex; justify-content:center; align-items:center">
                                <div class="card p-4" style=" display:flex; justify-content:center; align-items:center">
                                    <div class="card-body">
                                        @if (session()->has('message'))
                                            <p class="alert alert-info">
                                                {{ session()->get('message') }}
                                            </p>
                                        @endif
                                        <form method="POST" action="{{ route('verify.store') }}">
                                            {{ csrf_field() }}
                                            <h1>Verificación en dos pasos</h1>
                                            <p class="text-muted">
                                                Has recibido un correo electrónico que contiene el código de verificación en dos pasos. 
                                                Si no lo has recibido, presiona&nbsp;<a href="{{ route('verify.resend') }}">Aquí</a>.
                                            </p>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-lock"></i>
                                                    </span>
                                                </div>
                                                <input name="two_factor_code" type="text" class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" required autofocus placeholder="Codigo de Verificacion">
                                                @if ($errors->has('two_factor_code'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('two_factor_code') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-6" style="display:flex; justify-content:center">
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        Verificar
                                                    </button>
                                                </div>
                                                <div class="col-6 text-right" style="display:flex; justify-content:center">
                                                    <a class="btn btn-danger px-4" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                                        Cancelar
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

