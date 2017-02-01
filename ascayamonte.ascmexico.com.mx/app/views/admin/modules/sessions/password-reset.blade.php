@extends('admin.layouts.sessions')


@section('content')


    <!-- Reminder Block -->
    <div class="block animation-fadeInQuickInv">
        <!-- Reminder Title -->
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="{{ route('admin.sessions.create') }}" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Iniciar sesión"><i class="fa fa-user"></i></a>
            </div>
            <h2>Establecer</h2>
        </div>
        <!-- END Reminder Title -->

        <!-- Reminder Form -->
        {{ Form::open(array('id' => 'form-password-reset', 'route' => array('admin.sessions.password-reset.post', $email, $token), 'class' => 'form-horizontal', 'method' => 'POST')) }}

        <div class="form-group  {{ $errors->first('password') ? 'has-error' : '' }}">
            <div class="col-xs-12">
                {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Nueva contraseña..')) }}

                @if( $errors->first('password'))
                    <p class="help-block">{{ $errors->first('password')  }}</p>
                @endif
            </div>
        </div>

        <div class="form-group  {{ $errors->first('password_confirmation') ? 'has-error' : '' }}">
            <div class="col-xs-12">
                {{ Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Confirmar contraseña..')) }}

                @if( $errors->first('password_confirmation'))
                    <p class="help-block">{{ $errors->first('password_confirmation')  }}</p>
                @endif
            </div>
        </div>




        <div class="form-group form-actions">
            <div class="col-xs-12 text-right">
                <button type="submit" class="btn btn-effect-ripple btn-sm btn-primary"><i class="fa fa-check"></i> Establecer contraseña</button>
            </div>
        </div>
        {{ Form::close() }}
        <!-- END Reminder Form -->
    </div>
    <!-- END Reminder Block -->



@stop


@section('javascript')

    <!-- Load and execute javascript code used only in this page -->
    {{ HTML::script('assets/admin/js/pages/readyPasswordReset.js') }}
    <script>$(function(){ ReadyPasswordReset.init(); });</script>

@stop