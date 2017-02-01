<?php $input_old = Input::old() ?>

@if (empty($year))
{{-- Año -  SELECT2 --}}
<div class="form-group {{ $errors->first('year') ? 'has-error' : '' }}">

    {{ Form::label('year', 'Año', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-5">
        <?php $year = date('Y'); ?>
        {{ Form::select('year', [($year-4) => ($year-4), ($year-3) => ($year-3),($year-2) => ($year-2),($year-1) => ($year-1),$year => $year, ($year+1) => ($year+1), ($year+2) => ($year+2), ($year+3) => ($year+3), ($year+4) => ($year+4)], null, array('class' => 'select-select2', 'placeholder' => '', 'data-placeholder' => 'Zona', 'style' => 'width: 100%;', 'id' => 'year')) }}

        @if( $errors->first('year'))
            <span class="help-block">{{ $errors->first('year')  }}</span>
        @endif

    </div>
</div>
@else
    {{-- Año -  TEXT --}}
    <div class="form-group {{ $errors->first('year') ? 'has-error' : '' }}">

        {{ Form::label('year', 'Año', array('class' => 'col-md-4 control-label'))  }}
        <div class="col-md-5">
            {{ Form::text('year', $year->year, array('class' => 'form-control', 'placeholder' => '', 'id' => 'year', 'disabled' => true)) }}
            @if( $errors->first('year'))
                <p class="help-block">{{ $errors->first('year')  }}</p>
            @endif
        </div>
    </div>
@endif




<details style="margin-bottom: 1em;" open>


    <summary style="font-size: 1.2em;">Cuota ordinaria Mensual</summary>

    @foreach($months as $monthId => $monthName)
        {{-- Tarifa -  TEXT --}}
        <div class="form-group {{ $errors->first('fees_ordinary') ? 'has-error' : '' }}">

            {{ Form::label('fees_ordinary', $monthName, array('class' => 'col-md-4 control-label', 'id' => ('fees_ordinary-' . $monthId)))  }}
            <div class="col-md-8">
                <span class="help-block">Tarifa por m<sup>2</sup> del 1 al 15</span>
                {{ Form::text('fees_ordinary[' . $monthId . '][rate_first_days]', empty($feesordinaries[$monthId]['rate_first_days']) ? null : $feesordinaries[$monthId]['rate_first_days'], array('class' => 'form-control', 'placeholder' => '0.00', 'id' => ('fees_ordinary-' . $monthId))) }}

                <span class="help-block">Tarifa por m<sup>2</sup></span>
                {{ Form::text('fees_ordinary[' . $monthId . '][rate]', empty($feesordinaries[$monthId]['rate']) ? null : $feesordinaries[$monthId]['rate'], array('class' => 'form-control', 'placeholder' => '0.00', 'id' => ('fees_ordinary-' . $monthId))) }}

            </div>
        </div>
    @endforeach
</details>

<details style="margin-bottom: 1em;">


    <summary style="font-size: 1.2em;">Cuota ordinaria Anual</summary>

    @foreach($months as $monthId => $monthName)
        @if ( $monthId <= 4)
            {{-- Tarifa -  TEXT --}}
            <div class="form-group {{ $errors->first('fees_ordinary_yearly') ? 'has-error' : '' }}">

                {{ Form::label('fees_ordinary_yearly', $monthName, array('class' => 'col-md-4 control-label', 'id' => ('fees_ordinary-' . $monthId)))  }}
                <div class="col-md-8">

                    <span class="help-block">Tarifa por m<sup>2</sup></span>
                    {{ Form::text('fees_ordinary_yearly[' . $monthId . '][rate]', empty($feesordinariesyearly[$monthId]['rate']) ? null : $feesordinariesyearly[$monthId]['rate'], array('class' => 'form-control', 'placeholder' => '0.00', 'id' => ('fees_ordinary_yearly-' . $monthId))) }}

                    <span class="help-block">Descuento por pago anual</span>
                    {{ Form::text('fees_ordinary_yearly[' . $monthId . '][discount_yearly]', empty($feesordinariesyearly[$monthId]['discount_yearly']) ? null : $feesordinariesyearly[$monthId]['discount_yearly'], array('class' => 'form-control', 'placeholder' => '0', 'id' => ('fees_ordinary_yearly-' . $monthId))) }}

                </div>
            </div>
        @endif
    @endforeach
</details>

<details style="margin-bottom: 1em;">
    <summary style="font-size: 1.2em;">Cuota Especial de Obra</summary>

    {{-- Tarifa -  TEXT --}}
    <div class="form-group {{ $errors->first('fees_extraordinary_special_work_rate') ? 'has-error' : '' }}">

        {{ Form::label('fees_extraordinary_special_work_rate', 'Tarifa', array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_special_work_rate')))  }}
        <div class="col-md-8">

            <span class="help-block">Tarifa por m<sup>2</sup></span>
            {{ Form::text('fees_extraordinary_special_work_rate', empty($feesextraordinaryspecialworkrate) ? null : $feesextraordinaryspecialworkrate, array('class' => 'form-control', 'placeholder' => '0.00', 'id' => ('fees_extraordinary_special_work_rate'))) }}

        </div>
    </div>

    {{-- Tarifa -  TEXT --}}
    <div class="form-group {{ $errors->first('fees_extraordinary_special_work') ? 'has-error' : '' }}">

        {{ Form::label('fees_extraordinary_special_work_money', 'Diciembre', array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_special_work-12')))  }}
        <div class="col-md-8">

            <span class="help-block">Cantidad</span>
            {{ Form::text('fees_extraordinary_special_work_money[12][mount]', empty($feesextraordinaryspecialwork['12']['mount']) ? null : $feesextraordinaryspecialwork['12']['mount'], array('class' => 'form-control', 'placeholder' => '30,000.00', 'id' => ('fees_extraordinary_special_work_money-12'))) }}

        </div>
    </div>

    {{-- Tarifa -  TEXT --}}
    <div class="form-group {{ $errors->first('fees_extraordinary_special_work') ? 'has-error' : '' }}">

        {{ Form::label('fees_extraordinary_special_work_money', 'Enero', array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_special_work-1')))  }}
        <div class="col-md-8">

            <span class="help-block">Cantidad</span>
            {{ Form::text('fees_extraordinary_special_work_money[1][mount]', empty($feesextraordinaryspecialwork['1']['mount']) ? null : $feesextraordinaryspecialwork['1']['mount'], array('class' => 'form-control', 'placeholder' => '10,000.00', 'id' => ('fees_extraordinary_special_work_money-1'))) }}

        </div>
    </div>

    {{-- Tarifa -  TEXT --}}
    <div class="form-group {{ $errors->first('fees_extraordinary_special_work') ? 'has-error' : '' }}">

        {{ Form::label('fees_extraordinary_special_work_money', 'Febrero', array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_special_work-2')))  }}
        <div class="col-md-8">

            <span class="help-block">Cantidad</span>
            {{ Form::text('fees_extraordinary_special_work_money[2][mount]', empty($feesextraordinaryspecialwork['2']['mount']) ? null : $feesextraordinaryspecialwork['2']['mount'], array('class' => 'form-control', 'placeholder' => '10,000.00', 'id' => ('fees_extraordinary_special_work_money-2'))) }}

        </div>
    </div>

    {{-- Tarifa -  TEXT --}}
    <div class="form-group {{ $errors->first('fees_extraordinary_special_work') ? 'has-error' : '' }}">

        {{ Form::label('fees_extraordinary_special_work_money', 'Marzo', array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_special_work-3')))  }}
        <div class="col-md-8">

            <span class="help-block">Cantidad</span>
            {{ Form::text('fees_extraordinary_special_work_money[3][mount]', empty($feesextraordinaryspecialwork['3']['mount']) ? null : $feesextraordinaryspecialwork['3']['mount'], array('class' => 'form-control', 'placeholder' => '10,000.00', 'id' => ('fees_extraordinary_special_work_money-3'))) }}

        </div>
    </div>

    <p>Complementario</p>

    {{-- Tarifa -  TEXT --}}
    <div class="form-group {{ $errors->first('fees_extraordinary_special_work') ? 'has-error' : '' }}">

        {{ Form::label('fees_extraordinary_special_work_percent', 'Octubre', array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_special_work-10')))  }}
        <div class="col-md-8">

            <span class="help-block">Porcentaje</span>
            {{ Form::text('fees_extraordinary_special_work_percent[10][percent]', empty($feesextraordinaryspecialwork['10']['percent']) ? null : $feesextraordinaryspecialwork['10']['percent'], array('class' => 'form-control', 'placeholder' => '50', 'id' => ('fees_extraordinary_special_work_percent-10'))) }}

        </div>
    </div>

    {{-- Tarifa -  TEXT --}}
    <div class="form-group {{ $errors->first('fees_extraordinary_special_work') ? 'has-error' : '' }}">

        {{ Form::label('fees_extraordinary_special_work_percent', 'Noviembre', array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_special_work-11')))  }}
        <div class="col-md-8">

            <span class="help-block">Porcentaje</span>
            {{ Form::text('fees_extraordinary_special_work_percent[11][percent]', empty($feesextraordinaryspecialwork['11']['percent']) ? null : $feesextraordinaryspecialwork['11']['percent'], array('class' => 'form-control', 'placeholder' => '50', 'id' => ('fees_extraordinary_special_work_percent-11'))) }}

        </div>
    </div>
</details>

<details style="margin-bottom: 1em;">
    <summary style="font-size: 1.2em;">Cuota Especial de Reserva</summary>

    @foreach($months as $monthId => $monthName)
        @if ($monthId == 12)
            {{-- Tarifa -  TEXT --}}
            <div class="form-group {{ $errors->first('fees_extraordinary_reserve') ? 'has-error' : '' }}">

                {{ Form::label('fees_extraordinary_reserve', $monthName, array('class' => 'col-md-4 control-label', 'id' => ('fees_extraordinary_reserve-' . $monthId)))  }}
                <div class="col-md-8">

                    <span class="help-block">Tarifa por m<sup>2</sup></span>
                    {{ Form::text('fees_extraordinary_reserve[' . $monthId . '][rate]', empty($feesextraordinariesreserves[$monthId]) ? null : $feesextraordinariesreserves[$monthId], array('class' => 'form-control', 'placeholder' => '0.00', 'id' => ('fees_extraordinary_reserve-' . $monthId))) }}

                </div>
            </div>
        @endif
    @endforeach
</details>

<details style="margin-bottom: 1em;">
    <summary style="font-size: 1.2em;">Cuota Especial Adeudo 2010</summary>

    {{--@foreach($months as $monthId => $monthName)--}}
        {{-- Tarifa -  TEXT --}}
        <div class="form-group {{ $errors->first('fees_debs_2010') ? 'has-error' : '' }}">

            {{ Form::label('fees_debs_2010', 'Tarifa', array('class' => 'col-md-4 control-label', 'id' => ('fees_debs_2010')))  }}
            <div class="col-md-8">

                <span class="help-block">Tarifa por m<sup>2</sup></span>
                {{ Form::text('fees_debs_2010', empty($feesdebs2010) ? null : $feesdebs2010, array('class' => 'form-control', 'placeholder' => '0.00 ', 'id' => ('fees_debs_2010'))) }}

            </div>
        </div>
    {{--@endforeach--}}
</details>

<details style="margin-bottom: 1em;">
    <summary style="font-size: 1.2em;">Intereses por mes</summary>

    @foreach($months as $monthId => $monthName)
        {{-- Tarifa -  TEXT --}}
        <div class="form-group {{ $errors->first('interests') ? 'has-error' : '' }}">

            {{ Form::label('interests', $monthName, array('class' => 'col-md-4 control-label', 'id' => ('interests-' . $monthId)))  }}
            <div class="col-md-8">
                {{ Form::text('interests[' . $monthId . '][rate]', empty($interests[$monthId]) ? null : $interests[$monthId], array('class' => 'form-control', 'placeholder' => '0.00', 'id' => ('interests-' . $monthId))) }}
                @if( $errors->first('interests'))
                    <p class="help-block">{{ $errors->first('interests')  }}</p>
                @endif
            </div>
        </div>
    @endforeach

</details>



{{-- FORM ACTIONS --}}
<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        {{ Form::submit('Guardar', array('class' => 'btn btn-effect-ripple btn-primary')) }}
        {{ Form::reset('Limpiar formulario', array('class' => 'btn btn-effect-ripple btn-danger')) }}
    </div>
</div>