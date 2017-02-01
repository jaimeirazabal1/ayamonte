<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ayamonte.mx</title>
    <style>

        /*th,td,p,div,b {margin:0;padding:0}*/
        html{margin:20px 20px}

        body {
            font-family: Arial, sans-serif;
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            /*position: fixed;*/
            font-size: 11px;
        }
        .header {
            top: 0px;
        }
        .footer {
            bottom: 0px;
        }
        .pagenum:before {
            content: counter(page);
        }

        .clearfix:before,
        .clearfix:after {
            content: " ";
            display: table;
        }

        #content {
            margin-top: 40px;
        }

        .clearfix:after {
            clear: both;
        }

        .clearfix {
            *zoom: 1;
        }

        .left {
            float: left;
        }

        .right {
            float: right;
        }

        .col-50 {
            width: 50%;
        }

        .col-100 {
            width: 100%;
        }

        .debs-table {
            border: solid 2px #000;
            padding: 5px;
        }

        .debs-table td {
            padding: 5px;
            font-size: 9px;
        }

        .main-title {
            font-size: 10px;
            font-weight: bold;
        }


        .header-data {
            font-size: 11px;
            font-weight: bold;
        }

        .table-info  {
            font-size: 10px;
            text-align: left;
        }

        .table-info th {
            text-align: left;
        }

        .table-info td {
            padding-right: 5px;
            font-size: 10px;
        }

        .table-info td.money {
            text-align: right;
        }

        .table-info td.center {
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="header">
        <p class="main-title">ESTADO DE CUENTA DE CONDOMINIO COMPUESTO AYAMONTE P EN C</p>

        <table width="100%" class="header-data">
            <tr>
                <td width="50%">
                    <img height="70px" src="{{ public_path() . '/assets/admin/img/logo.jpg' }}" alt="Ayamonte" />
                </td>
                <td width="50%">
                    <table width="100%">
                        <tr>
                            <td>
                                <p>Número oficial</p>
                            </td>
                            <td>
                                <p>
                                    {{ $lot->official_number }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table  width="100%" class="header-data">
            <tr>
                <td width="20%" valign="top">
                    Condomino<br />
                    Domicilio<br />
                    Colonia<br />
                    C.P.<br />
                    Ciudad/Estado
                </td>
                <td width="50%"  valign="top">
                    {{ $lot->owner }}<br />
                </td>
                <td width="30%" valign="top" align="right">
                    <table width="100%" class="debs-table" cellspacing="0" cellpadding="">

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
                                <td colspan="2">RESÚMEN DEL ADEUDO</td>
                            </tr>
                            <tr>
                                <td>
                                    Coutas Ordinarias de Mantenimiento
                                </td>
                                <td align="right">
                                    ${{ convertIntegerToMoney($amount_ordinary) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Intereses Moratorios
                                </td>
                                <td align="right">
                                    ${{ convertIntegerToMoney($amount_interest) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Cuotas Extraordinarias
                                </td>
                                <td align="right">
                                    ${{ convertIntegerToMoney($amount_extra) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td align="right">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Adeudo Total
                                </td>
                                <td align="right">
                                    ${{ convertIntegerToMoney($balance) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    Condomino<br />
                    Plaza<br />
                    Número Oficial<br />
                    Terreno
                </td>
                <td valign="top">
                    {{ $lot->owner }}<br />
                    {{ $lot->square->name }} Lote {{ $lot->lot }}<br />
                    {{ $lot->official_number }}<br />
                    {{ $lot->m2 }}
                </td>
                <td valign="top">

                </td>
            </tr>
        </table>
        <!--Page <span class="pagenum"></span>-->
    </div>
    <div class="footer">
        Page <span class="pagenum"></span>
    </div>


    <div id="content">
        <table class="table-info">
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
                    {{--<th><small>Estatus</small></th>--}}
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
                        <td>01/{{ getShortMonth($transaction->month_id) }}/{{ $transaction->year->year }}</td>
                        <td>{{ getTransactionType($transaction->transaction) }} {{ getShortMonth($transaction->month_id) }} {{ substr($transaction->year->year, 2, 2) }}</td>
                        <td class="money">${{ convertIntegerToMoney($transaction->amount) }}</td>
                        <td class="center">{{ $transaction->monthly_interest }}%</td>
                        <td class="money">${{ convertIntegerToMoney($transaction->indebted_amount) }}</td>
                        <td class="center">{{ $transaction->indebted_months }}</td>
                        <td>
                            @if ($start_date->month == $end_date->month and $start_date->year == $end_date->year)
                                {{ getMonth($start_date->month) }} {{ $start_date->year }}
                            @else
                                {{ getMonth($start_date->month) }} {{ $start_date->year }} a {{ getMonth($end_date->month) }} {{ $end_date->year }}
                            @endif

                        </td>
                        <td class="money">${{ convertIntegerToMoney($transaction->indebted_amount * $transaction->indebted_months) }}</td>
                        {{--<td>{{ getTransactionStatus($transaction->status) }}</td>--}}
                        <td class="money">${{ convertIntegerToMoney($transaction->balance_partial) }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

</body>
</html>