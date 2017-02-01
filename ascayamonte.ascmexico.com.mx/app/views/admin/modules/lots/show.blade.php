<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Estado de cuenta</h4>
</div>
<div class="modal-body">
    <table width="80%">
        <tbody>
            <tr>
                <td>
                    Número oficial:
                </td>
                <td>{{ $lot->official_number }}</td>
            </tr>
            <tr>
                <td>Lote:</td>
                <td>{{ $lot->lot }}</td>
            </tr>
            <tr>
                <td>Propietario:</td>
                <td>{{ $lot->owner }}</td>
            </tr>
            <tr>
                <td>m<sup>2</sup>:</td>
                <td>{{ number_format($lot->m2, 2) }}</td>
            </tr>
            <tr>
                <td>Fecha de compra:</td>
                <td>{{ $lot->purchase_date->format('Y-m') }}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table width="80%">
        <?php
            $amount_ordinary = 0;
            $amount_interest = 0;
            $amount_extra    = 0;
            $balance         = 0;
        ?>
        @foreach($transactions as $transaction)
            <?php
                    switch($transaction->transaction)
                    {
                        case 'fee_ordinary_maintenance':
                            $amount_ordinary += $transaction->amount;
                            break;
                        case 'fee_extraordinary_reserve':
                            $amount_extra += $transaction->amount;
                            break;
                        case 'fee_special_work':
                            $amount_extra += $transaction->amount;
                            break;
                        case 'fee_debts_2010':
                            $amount_extra += $transaction->amount;
                            break;
                    }

                    $amount_interest += ($transaction->indebted_months * $transaction->indebted_amount);

                    $balance += ($transaction->balance_partial);
            ?>
        @endforeach

        <tbody>
            <tr>
                <td>
                    Cuotas ordinarias mantenimiento:
                </td>
                <td>${{ convertIntegerToMoney($amount_ordinary) }}</td>
            </tr>
            <tr>
                <td>Intereses moratorios:</td>
                <td>${{ convertIntegerToMoney($amount_interest) }}</td>
            </tr>
            <tr>
                <td>Cuotas extraordinarias:</td>
                <td>${{ convertIntegerToMoney($amount_extra) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Adeudo total</td>
                <td><strong>${{ convertIntegerToMoney($balance) }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @if ($lot->balance_positive > 0)
            <tr>
                <td>Saldo a favor: </td>
                <td><strong>${{ convertIntegerToMoney($lot->balance_positive) }}</strong></td>
            </tr>
            @endif
        </tbody>
    </table>
    <br/>
    @if ( count($transactions))
    <table class="table table-bordered table-striped" width="100%">
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

            <tr>
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
                <td>${{ convertIntegerToMoney($transaction->balance_partial) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="well">
            No existen adeudos.
        </p>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
    <a href="{{ route('admin.lots.download', $lot->id) }}" target="_blank" class="btn btn-primary"><i class="fa fa-download"></i> Descargar PDF</a>
</div>