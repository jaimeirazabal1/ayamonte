{{-- Título -  TEXT --}}
<div class="form-group {{ $errors->first('title') ? 'has-error' : '' }}">

    {{ Form::label('title', 'Título', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        {{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'title')) }}
        @if( $errors->first('title'))
            <p class="help-block">{{ $errors->first('title')  }}</p>
        @endif
    </div>
</div>

{{-- Descripción -  TEXTAREA --}}
<div class="form-group {{ $errors->first('description')? 'has-error' : '' }}">

    {{ Form::label('description', 'Descripción', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        {{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 7,  'placeholder' => '', 'id' => 'description')) }}
        @if( $errors->first('description'))
            <span class="help-block">{{ $errors->first('description')  }}</span>
        @endif
    </div>
</div>


{{-- Inactivo / Activo -  SWITCH --}}
<div class="form-group {{ $errors->first('status') ? 'has-error' : '' }}">

    {{ Form::label('status', 'Inactivo / Activo', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-8">
        <label class="switch switch-success">{{ Form::checkbox('status', 'true') }}<span></span></label>


        @if( $errors->first('status'))
            <span class="help-block">{{ $errors->first('status')  }}</span>
        @endif
    </div>
</div>

{{-- FORM ACTIONS --}}
<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        {{ Form::submit('Guardar', array('class' => 'btn btn-effect-ripple btn-primary')) }}
        {{ Form::reset('Limpiar formulario', array('class' => 'btn btn-effect-ripple btn-danger')) }}
    </div>
</div>