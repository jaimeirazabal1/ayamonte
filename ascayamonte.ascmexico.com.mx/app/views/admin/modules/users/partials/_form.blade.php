{{-- Nombre -  TEXT --}}
<div class="form-group {{ $errors->first('first_name') ? 'has-error' : '' }}">

    {{ Form::label('first_name', 'Nombre(s):', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        {{ Form::text('first_name', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'first_name')) }}
        @if( $errors->first('first_name'))
            <p class="help-block">{{ $errors->first('first_name')  }}</p>
        @endif
    </div>
</div>


{{-- Apellido -  TEXT --}}
<div class="form-group {{ $errors->first('last_name') ? 'has-error' : '' }}">

    {{ Form::label('last_name', 'Apellidos:', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        {{ Form::text('last_name', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'last_name')) }}
        @if( $errors->first('last_name'))
            <p class="help-block">{{ $errors->first('last_name')  }}</p>
        @endif
    </div>
</div>

{{-- E-mail -  TEXT --}}
<div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">

    {{ Form::label('email', 'E-mail:', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'email')) }}
        @if( $errors->first('email'))
            <p class="help-block">{{ $errors->first('email')  }}</p>
        @endif
    </div>
</div>


{{-- Perfil de usuario -  SELECT2 --}}
<div class="form-group {{ $errors->first('group_id') ? 'has-error' : '' }}">

    {{ Form::label('group_id', 'Perfil de usuario', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-5">
        {{ Form::select('group_id', $profiles, $profile_user, array('class' => 'select-select2', 'placeholder' => '', 'data-placeholder' => 'Perfil de usuario', 'style' => 'width: 100%;', 'id' => 'group_id')) }}

        @if( $errors->first('group_id'))
            <span class="help-block">{{ $errors->first('group_id')  }}</span>
        @endif

    </div>
</div>


{{-- Contraseña: -  PASSWORD --}}
<div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">

    {{ Form::label('password', 'Contraseña:', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        {{ Form::password('password', array('class' => 'form-control', 'placeholder' => '', 'id' => 'password')) }}
        @if( $errors->first('password'))
            <p class="help-block">{{ $errors->first('password')  }}</p>
        @endif
    </div>
</div>



{{-- Confirmar contraseña: -  PASSWORD --}}
<div class="form-group {{ $errors->first('password_confirmation') ? 'has-error' : '' }}">

    {{ Form::label('password_confirmation', 'Confirmar contraseña:', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        {{ Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => '', 'id' => 'password_confirmation')) }}
        @if( $errors->first('password_confirmation'))
            <p class="help-block">{{ $errors->first('password_confirmation')  }}</p>
        @endif
    </div>
</div>


{{-- Inactivo / Activo: -  SWITCH --}}
<div class="form-group {{ $errors->first('activated') ? 'has-error' : '' }}">

    {{ Form::label('activated', 'Inactivo / Activo:', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        <label class="switch switch-success">{{ Form::checkbox('activated', 'true') }}<span></span></label>


        @if( $errors->first('activated'))
            <span class="help-block">{{ $errors->first('activated')  }}</span>
        @endif
    </div>
</div>



{{-- FORM ACTIONS --}}
<div class="form-group form-actions">
    <div class="col-md-8 col-md-offset-4">
        {{ Form::submit('Guardar', array('class' => 'btn btn-effect-ripple btn-primary')) }}
        {{ Form::reset('Limpiar formulario', array('class' => 'btn btn-effect-ripple btn-danger')) }}
    </div>
</div>