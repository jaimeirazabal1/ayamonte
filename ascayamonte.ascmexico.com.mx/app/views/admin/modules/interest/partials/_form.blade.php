{{-- Año -  SELECT2 --}}
<div class="form-group {{ $errors->first('year_id') ? 'has-error' : '' }}">

    {{ Form::label('year_id', 'Año', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-5">
        {{ Form::select('year_id', $years, null, array('class' => 'select-select2', 'placeholder' => '', 'data-placeholder' => 'Zona', 'style' => 'width: 100%;', 'id' => 'year_id')) }}

        @if( $errors->first('year_id'))
            <span class="help-block">{{ $errors->first('year_id')  }}</span>
        @endif

    </div>
</div>

<fieldset>

    <legend>Interés por mes</legend>
    @foreach($months as $monthId => $montName)


    {{-- Tarifa por m2 -  TEXT --}}
    <div class="form-group {{ $errors->first('rate') ? 'has-error' : '' }}">

        {{ Form::label('rates', $montName, array('class' => 'col-md-4 control-label'))  }}
        <div class="col-md-5">
            {{ Form::text('rates[' . $monthId . '][rate]', null, array('class' => 'form-control', 'placeholder' => '0.00', 'id' => 'rate')) }}
            @if( $errors->first('rate'))
                <p class="help-block">{{ $errors->first('rate')  }}</p>
            @endif
        </div>
    </div>

    @endforeach

    </fieldset>

{{-- FORM ACTIONS --}}
<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        {{ Form::submit('Guardar', array('class' => 'btn btn-effect-ripple btn-primary')) }}
        {{ Form::reset('Limpiar formulario', array('class' => 'btn btn-effect-ripple btn-danger')) }}
    </div>
</div>