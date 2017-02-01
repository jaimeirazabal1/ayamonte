@extends('admin.layouts.sessions')


@section('content')


        <!-- Login Block -->
        <div class="block animation-fadeInQuickInv">
            <!-- Login Title -->
            <div class="block-title">
                <div class="block-options pull-right">
                    <a href="{{ route('admin.sessions.password-recovery') }}" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="¿Olvidaste tu contraseña?"><i class="fa fa-exclamation-circle"></i></a>
                    {{--<!--a href="page_ready_register.html" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Create new account"><i class="fa fa-plus"></i></a-->--}}
                </div>
                <h2>Iniciar sesión</h2>
            </div>
            <!-- END Login Title -->

            <!-- Login Form -->
            {{ Form::open(array('id' => 'form-login', 'method' => 'POST', 'class' => 'form-horizontal')) }}
                <div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
                    <div class="col-xs-12">
                        {{ Form::text('email', null, array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Correo electrónico..')) }}

                        @if( $errors->first('email'))
                            <p class="help-block">{{ $errors->first('email')  }}</p>
                        @endif

                    </div>
                </div>
                <div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">

                    <div class="col-xs-12">
                        {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Contraseña..')) }}

                        @if( $errors->first('password'))
                            <p class="help-block">{{ $errors->first('password')  }}</p>
                        @endif

                    </div>
                </div>
                <div class="form-group form-actions">
                    <div class="col-xs-8">
                        <label class="csscheckbox csscheckbox-primary">
                            {{ Form::checkbox('login-remember-me', '1', false, array('id' => 'login-remember-me')) }}
                            <span></span>
                            ¿Recordar sesión?
                        </label>

                    </div>
                    <div class="col-xs-4 text-right">
                        <button type="submit" class="btn btn-effect-ripple btn-sm btn-primary"><i class="fa fa-check"></i> Ingresar</button>
                    </div>
                </div>
            {{ Form::close() }}
            <!-- END Login Form -->
        </div>
        <!-- END Login Block -->

@stop


@section('javascript')

    <!-- Load and execute javascript code used only in this page -->
    {{ HTML::script('assets/admin/js/pages/readyLogin.js') }}
    <script>$(function(){ ReadyLogin.init(); });</script>

@stop