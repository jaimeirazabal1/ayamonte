@extends('admin.layouts.master')


@section('title', 'Pagos')

@section('module', 'PAGOS')
@section('content')

    <!-- Section Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Pagos</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>{{ link_to_route('admin.dashboard', 'Dashboard') }}</li>
                        <li>Pagos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Section Header -->

    <!-- Tables Row -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Partial Responsive Block -->
            <div class="block">
                <!-- Partial Responsive Title -->
                <div class="block-title">
                    <h2>Listado de Pagos</h2>
                    <small class="label label-sm label-default">{{ $payments->getTotal() }} registros</small>
                    <div class="block-options pull-right">
                        <a href="{{ route('admin.payments.create') }}" class="btn btn-effect-ripple btn-success" data-toggle="tooltip" title="" style="overflow: hidden; position: relative;" data-original-title="Agregar registro"><i class="fa fa-plus"></i> Generar pago</a>
                    </div>
                </div>
                <!-- END Partial Responsive Title -->

                @if( $payments->count() )
                    <table class="table table-striped table-borderless table-vcenter">
                        <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Condominio</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Tipo</th>
                            <th>Fecha de pago</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>
                                    <span class="label label-default">{{ $payment->lot->lot }}</span> {{ $payment->lot->owner }} <br />
                                    <small class="text-muted">Número oficial condominio: {{ $payment->lot->official_number  }}</small>
                                </td>
                                <td>
                                    <strong>{{ $payment->concept }}</strong>
                                </td>
                                <td>
                                    ${{ convertIntegerToMoney($payment->amount) }}
                                </td>
                                <td>
                                    @if($payment->type == 'cash')
                                        <span class="label label-success">Efectivo</span>
                                    @elseif($payment->type == 'card')
                                        <span class="label label-info">Tarjeta</span><br/>
                                        @if( ! empty($payment->payment_reference_type))
                                            <small class="text-muted">Terminación de tarjeta: {{ $payment->payment_reference_type }}</small>
                                        @endif
                                    @elseif($payment->type == 'transfer')
                                        <span class="label label-info">Transferencia</span>
                                    @elseif($payment->type == 'deposit')
                                        <span class="label label-info">Deposito</span>
                                    @elseif($payment->type == 'cheque')
                                        <span class="label label-info">Cheque</span><br/>
                                        @if( ! empty($payment->payment_reference_type))
                                            <small class="text-muted">Número de cheque: {{ $payment->payment_reference_type }}</small>
                                        @endif

                                    @endif
                                </td>
                                <td class="hidden-sm hidden-xs">
                                    {{ $payment->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    @if ( ! empty($payment->comments))
                                        <button data-placement="left" data-trigger="hover" type="button" class="btn btn-xs btn-default" data-toggle="popover" title="Comentarios" data-content="{{ $payment->comments }}"><i class="fa fa-comment"></i></button>
                                    @else
                                        -
                                    @endif

                                        <a target="_blank" href="{{ route('admin.payments.download', $payment->id) }}" class="btn btn-xs btn-default"><i class="fa fa-print" title="Imprimir recibo"></i></a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="text-center">
                        {{ $payments->links() }}
                    </div>

                @else
                    <p>
                        <strong>No</strong> existen registros de Pagos aún.
                    </p>
                @endif
            </div>
            <!-- END Partial Responsive Block -->
        </div>

    </div>
    <!-- END Tables Row -->


@stop


@section('javascript')

@stop