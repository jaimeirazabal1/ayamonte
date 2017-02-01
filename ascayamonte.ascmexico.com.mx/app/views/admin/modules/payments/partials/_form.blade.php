{{-- Condominio -  SELECT2 --}}
<div class="form-group {{ $errors->first('lot_id') ? 'has-error' : '' }}">

    {{ Form::label('lot_id', 'Condominio', array('class' => 'col-md-4 control-label')) }}
    <div class="col-md-8">
        {{ Form::select('lot_id', $lots, $lot_id, array('class' => 'select-select2', 'placeholder' => '', 'data-placeholder' => 'Condominio', 'style' => 'min-width: 100%; width: 100%;', 'id' => 'lot_id')) }}

        @if( $errors->first('lot_id'))
            <span class="help-block">{{ $errors->first('lot_id')  }}</span>
        @endif

    </div>
</div>
@if( isset($transactions) )
    @if (  count($transactions))
    <style>
        .selectable:hover {
            background: #eee;
            cursor:     pointer;
        }

        .row-active {
            background: #f8ca00;
        }

        .row-active:hover {
            background: #f8ca00;
        }
    </style>
    <div class="containes" style="margin: 0 auto; padding: 0 10px;">
    <br />
    <p class="text-center"><em>Selecciona haciendo un clic sobre el pago que desees liquidar.</em></p>
    <table class="table table-bordered" width="80%">
        <thead>
        <tr>
            <th><small>Periodo</small></th>
            <th><small>Concepto</small></th>
            <th><small>Cuota</small></th>
            <th><small>Interés moratorio</small></th>
            <th><small>Interés mensual M.N.</small></th>
            <th><small>Meses en mora</small></th>
            <th><small>Interés moratorio total</small></th>
            <th><small>Interés moratorio adeuda</small></th>
            <th><small>Estatus</small></th>
            <th><small>Saldo</small></th>
        </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)

                <?php
                    $start_date = \Carbon\Carbon::createFromFormat('Y-m', "{$transaction->year->year}-$transaction->month_id");
                    $end_date   = \Carbon\Carbon::createFromFormat('Y-m', "{$transaction->year->year}-$transaction->month_id")->addMonths($transaction->indebted_months);
                ?>

            <tr class="selectable">
                {{--<td><small>{{ $transaction->id }}</small></td>--}}
                <td>01/{{ getShortMonth($transaction->month_id) }}/{{ $transaction->year->year }}</td>
                <td>{{ getTransactionType($transaction->transaction) }} {{ getShortMonth($transaction->month_id) }} {{ substr($transaction->year->year, 2, 2) }}</td>
                <td>${{ convertIntegerToMoney($transaction->amount) }}</td>
                <td>{{ $transaction->monthly_interest }}%</td>
                <td>${{ convertIntegerToMoney($transaction->indebted_amount) }}</td>
                <td>{{ $transaction->indebted_months }}</td>
                <td>
                    @if ($start_date->month == $end_date->month and $start_date->year == $end_date->year)
                        {{ getMonth($start_date->month) }} {{ $start_date->year }}
                    @else
                        {{ getMonth($start_date->month) }} {{ $start_date->year }} a {{ getMonth($end_date->month) }} {{ $end_date->year }}
                    @endif

                </td>
                <td>${{ convertIntegerToMoney($transaction->indebted_amount * $transaction->indebted_months) }}</td>
                <td>{{ getTransactionStatus($transaction->status) }}</td>
                <td class="amount">${{ convertIntegerToMoney($transaction->balance_partial) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @else
        <p class="well text-center">
            No existen adeudos.
        </p>
    @endif
@endif

{{-- Monto a pagar -  TEXT --}}
<div class="form-group {{ $errors->first('amount') ? 'has-error' : '' }}">

    {{ Form::label('amount', 'Cantidad a pagar', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-md-5">
        <!-- 'disabled' => '' -->
        {{ Form::text('amount', convertIntegerToMoney($balance), array('class' => 'form-control', 'readonly' => 'readonly', 'placeholder' => '0.00', 'id' => 'amount')) }}

        <p class="help-block">Adeudo total: ${{ convertIntegerToMoney($balance) }}</p>

        @if ($apply_payment_yearly)
        <p class="help-block">
            El monto de pago anual es de: ${{ convertIntegerToMoney($yearly_amount) }}<br>
            Descuento aplicado de: {{ $discount }}% <small>(Pago en efectivo)</small><br>
            Descuento aplicado de: {{ ($discount - 5) }}% <small>(Pago con tarjeta)</small><br>
            Tarifa por m2: ${{ convertIntegerToMoney($rate_yearly) }}<br>
            Monto calculado total ya con descuento (Pago en efectivo): <strong>${{ convertIntegerToMoney($yearly_amount_with_discount_cash) }}</strong><br>
            Monto calculado total ya con descuento (Pago con tarjeta): <strong>${{ convertIntegerToMoney($yearly_amount_with_discount_card) }}</strong><br>
        </p>
        @endif

        @if( $errors->first('amount'))
            <p class="help-block">{{ $errors->first('amount')  }}</p>
        @endif
    </div>
</div>


@if ($apply_payment_yearly)

{{-- Aplicar pago por anualidad - CUSTOM CHECCKBOX --}}
<div class="form-group {{ $errors->first('apply_yearly') ? 'has-error' : '' }}">
    {{ Form::label('apply_yearly', 'Aplicar pago por anualidad ' . $year, array('class' => 'col-md-4 control-label'))  }}
    <div class="col-sm-8">
        <label class="csscheckbox csscheckbox-primary">
            {{ Form::checkbox('apply_yearly', true, null, array('id' => 'apply_yearly')) }}
            <span></span>
        </label>
        <p class="help-block">
            - Si es seleccionado, el pago se calculará de forma anual y se aplicará el descuento correspondiente en caso de que aplique*.
            <br>
            - Quedan {{ $meses_anio_actual_por_pagar }} mes(es) por pagar.
            <br/>
            <small>* El descuento solo es válido en los meses de Enero, Febrero, Marzo, y Abril.</small><br />
            <small>* El monto ingresado debe ser exacto al monto calculado.</small>
        </p>
        @if( $errors->first('apply_yearly'))
            <p class="help-block">{{ $errors->first('apply_yearly')  }}</p>
        @endif
    </div>
</div>

{{ Form::hidden('meses', $meses_anio_actual_por_pagar) }}
{{ Form::hidden('discount', $discount) }}
{{ Form::hidden('rate_yearly', $rate_yearly) }}
{{ Form::hidden('amount_yearly', $yearly_amount) }}
{{ Form::hidden('amount_yearly_with_discount_cash', $yearly_amount_with_discount_cash) }}
{{ Form::hidden('amount_yearly_with_discount_card', $yearly_amount_with_discount_card) }}


@endif



{{-- Pago con tarjeta de crédito/débito - CUSTOM CHECCKBOX --}}
{{--<div class="form-group {{ $errors->first('payment_with_card') ? 'has-error' : '' }}">
    {{ Form::label('payment_with_card', 'Pago con tarjeta de crédito/débito', array('class' => 'col-md-4 control-label'))  }}
    <div class="col-sm-8">
        <label class="csscheckbox csscheckbox-primary">
            {{ Form::checkbox('payment_with_card', true, null, array('id' => 'payment_with_card')) }}
            <span></span>
        </label>
        @if( $errors->first('payment_with_card'))
            <p class="help-block">{{ $errors->first('payment_with_card')  }}</p>
        @endif
    </div>
</div>--}}




@if ( ! empty($lot_id))

    {{-- Pago de otros conceptos o pago de adeudo a condominio - CUSTOM CHECCKBOX --}}
    <div class="form-group {{ $errors->first('payment_type_concept') ? 'has-error' : '' }}">
        {{ Form::label('payment_type_concept', 'Pago de otros conceptos', array('class' => 'col-md-4 control-label'))  }}
        <div class="col-sm-8">
            <label class="csscheckbox csscheckbox-primary">
                {{ Form::checkbox('payment_type_concept', true, null, array('id' => 'payment_type_concept', 'data-text' => ($debt_only_current_month ? ( 'Cuota ordinaria de mantenimiento - ' . getMonth(date('n')) . '-' . date('Y')) : ('Abono a adeudos - ' . date('d') . '-' . getMonth(date('n')) . '-' . date('Y'))))) }}
                <span></span>
            </label>
            @if( $errors->first('payment_type_concept'))
                <p class="help-block">{{ $errors->first('payment_type_concept')  }}</p>
            @endif
        </div>
    </div>


    {{-- Concepto -  TEXT --}}
    <div class="form-group {{ $errors->first('concept') ? 'has-error' : '' }}">

        {{ Form::label('concept', 'Concepto', array('class' => 'col-md-4 control-label'))  }}
        <div class="col-md-5">
            {{ Form::text('concept', ($debt_only_current_month ? ( 'Cuota ordinaria de mantenimiento - ' . getMonth(date('n')) . '-' . date('Y')) : ('Abono a adeudos - ' . date('d') . '-' . getMonth(date('n')) . '-' . date('Y'))), array('class' => 'form-control', 'placeholder' => '', 'id' => 'concept')) }}
            @if( $errors->first('concept'))
                <p class="help-block">{{ $errors->first('concept')  }}</p>
            @endif
        </div>
    </div>

    {{-- Método de pago -  SELECT2 --}}
    <div class="form-group {{ $errors->first('type') ? 'has-error' : '' }}">

        {{ Form::label('type', 'Método de pago', array('class' => 'col-md-4 control-label'))  }}
        <div class="col-md-5">
            {{ Form::select('type', getPaymentTypeOptions(), null, array('class' => 'select-select2', 'placeholder' => '', 'data-placeholder' => 'Zona', 'style' => 'width: 100%;', 'id' => 'type')) }}

            @if( $errors->first('type'))
                <span class="help-block">{{ $errors->first('type')  }}</span>
            @endif

        </div>
    </div>
    
    {{-- Número de cheque o Terminación de la tarjeta -  TEXT --}}
    <div data-payments-field-reference-type style="display: none;" class="form-group {{ $errors->first('payment_reference_type') ? 'has-error' : '' }}">
    
        {{ Form::label('payment_reference_type', 'Número de cheque o Terminación de la tarjeta', array('class' => 'col-md-4 control-label'))  }}
        <div class="col-md-5">
            {{ Form::text('payment_reference_type', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'payment_reference_type')) }}
            @if( $errors->first('payment_reference_type'))
                <p class="help-block">{{ $errors->first('payment_reference_type')  }}</p>
            @endif
        </div>
    </div>


    {{-- Comentarios -  TEXTAREA --}}
    <div class="form-group {{ $errors->first('comments')? 'has-error' : '' }}">

        {{ Form::label('comments', 'Comentarios', array('class' => 'col-md-4 control-label'))  }}
        <div class="col-md-8">
            {{ Form::textarea('comments', null, array('class' => 'form-control', 'rows' => 7,  'placeholder' => '', 'id' => 'comments')) }}
            @if( $errors->first('comments'))
                <span class="help-block">{{ $errors->first('comments')  }}</span>
            @endif
        </div>
    </div>



{{-- FORM ACTIONS --}}
<div class="form-group form-actions">
    <div class="col-md-8 col-md-offset-4">

        {{ Form::submit('Pagar', array('class' => 'btn btn-effect-ripple btn-primary')) }}
    </div>
</div>
@endif