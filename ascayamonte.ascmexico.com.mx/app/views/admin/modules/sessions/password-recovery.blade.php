@extends('admin.layouts.sessions')


@section('content')



        <!-- Reminder Header -->
        <p class="text-muted text-center">
            Ingresa tu dirección de correo para restablecer tu contraseña. Puede que tengas que revisar en tu carpeta de spam.
        </p>
        <br />
        <!-- END Reminder Header -->

        <!-- Reminder Block -->
        <div class="block animation-fadeInQuickInv">
            <!-- Reminder Title -->
            <div class="block-title">
                <div class="block-options pull-right">
                    <a href="{{ route('admin.sessions.create') }}" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Iniciar sesión"><i class="fa fa-user"></i></a>
                </div>
                <h2>Restablecer</h2>
            </div>
            <!-- END Reminder Title -->

            <!-- Reminder Form -->
            {{ Form::open(array('id' => 'form-reminder', 'route' => 'admin.sessions.password-recovery.post', 'class' => 'form-horizontal', 'method' => 'POST')) }}
                <div class="form-group  {{ $errors->first('email') ? 'has-error' : '' }}">
                    <div class="col-xs-12">
                        {{ Form::text('email', null, array('id' => 'reminder-email', 'class' => 'form-control', 'placeholder' => 'Correo electrónico..')) }}

                        @if( $errors->first('email'))
                            <p class="help-block">{{ $errors->first('email')  }}</p>
                        @endif
                    </div>
                </div>
                <div class="form-group form-actions">
                    <div class="col-xs-12 text-right">
                        <button type="submit" class="btn btn-effect-ripple btn-sm btn-primary"><i class="fa fa-check"></i> Restablecer contraseña</button>
                    </div>
                </div>
            {{ Form::close() }}
            <!-- END Reminder Form -->
        </div>
        <!-- END Reminder Block -->


@stop


@section('javascript')

    <!-- Load and execute javascript code used only in this page -->
    {{ HTML::script('assets/admin/js/pages/readyReminder.js') }}
    <script>$(function(){ ReadyReminder.init(); });</script>

@stop