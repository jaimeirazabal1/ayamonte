@extends('admin.layouts.master')


@section('title', 'Condominios')
@section('module', 'CONDOMINIOS')


@section('content')

<!-- Section Header -->
<div class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <div class="header-section">
                <h1>Condominios</h1>
            </div>
        </div>
        <div class="col-sm-6 hidden-xs">
            <div class="header-section">
                <ul class="breadcrumb breadcrumb-top">
                    <li>{{ link_to_route('admin.dashboard', 'Dashboard') }}</li>
                    <li>Condominios</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END Section Header -->

<div class="row">
    <div class="col-lg-12">
        <div class="block">
            {{ Form::open(['method' =>'GET', 'route' => 'admin.lots.index', 'class' => 'form-inline', 'style' => 'margin-bottom: 15px;']) }}

            {{-- Búsqueda: -  TEXT --}}
            <div class="form-group" style="width: 50%">
                <label for="q">Búsqueda: </label>
                {{ Form::text('q', null, array('class' => 'form-control', 'placeholder' => 'Buscar por propietario, lote, cuenta, referencia.', 'id' => 'q', 'style' => 'width: 85%')) }}
            </div>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Buscar</button>
            <a href="{{ route('admin.lots.index') }}" class="btn btn-default">Ver todos</a>

            {{ Form::close() }}
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row">
    <div class="col-lg-12">
        <!-- Partial Responsive Block -->
        <div class="block">
            <!-- Partial Responsive Title -->
            <div class="block-title">
                <h2>Listado de condominios</h2>
                <small class="label label-sm label-default">{{ $lots->getTotal() }} registros</small>
                <div class="block-options pull-right">
                    <a href="{{ route('admin.lots.create') }}" class="btn btn-effect-ripple btn-success" data-toggle="tooltip" title="" style="overflow: hidden; position: relative;" data-original-title="Agregar registro"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <!-- END Partial Responsive Title -->



            @if( $lots->count() )
            <table class="table table-striped table-borderless table-vcenter">
                <thead>
                    <tr>
                        <th>No. oficial</th>
                        <th>Referencia</th>
                        <th>Cuenta</th>
                        <th>Plaza</th>
                        <th>Lote</th>
                        <th>Propietario</th>
                        <th style="width: 80px;" class="text-center"><i class="fa fa-flash"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lots as $lot)
                    <tr>
                        <td><strong>{{ $lot->official_number  }}</strong></td>
                        <td>{{ $lot->reference }}</td>
                        <td>{{ $lot->account_number }}</td>
                        <td>{{ $lot->square->name }}</td>
                        <td>{{ $lot->lot }}</td>
                        <td><b>{{ $lot->owner }}</b></td>
                        <td>
                            <a href="{{ route('admin.lots.edit', $lot->id) }}" class="btn btn-default btn-effect-ripple"><i class="fa fa-pencil"></i> Editar</a>
                            {{--<a href="{{ route('admin.lots.payments', $lot->id) }}" class="btn btn-default btn-effect-ripple"><i class="fa fa-money"></i> Pagos</a>--}}
                            <div class="btn-group">

                                <div class="btn-group">
                                    <a href="javascript:void(0)" data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false">Estado de cuenta <span class="caret"></span></a>
                                    <ul class="dropdown-menu dropdown-menu-right text-left">
                                        <li class="dropdown-header">
                                            <i class="fa fa-calculator pull-right"></i> <strong>ESTADO DE CUENTA</strong>
                                        </li>
                                        <li>
                                            <a data-toggle="modal" data-target="#estado-cuenta" href="{{ route('admin.lots.show', $lot->id) }}">
                                                <i class="fa fa-eye pull-right"></i>
                                                Ver
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.lots.send-by-email', $lot->id) }}">
                                                <i class="fa fa-envelope pull-right"></i>
                                                Enviar por email
                                            </a>
                                        </li>

                                        <li class="divider"></li>
                                        <li>
                                            <a href="{{ route('admin.lots.download', $lot->id) }}">
                                                <i class="fa fa-download pull-right"></i>
                                                Descargar PDF
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Form::open(array('route' => array('admin.lots.destroy', $lot->id), 'method' => 'DELETE', 'class' => 'form-inline','data-delete-confirmation' => '')) --}}
                                    {{-- Form::button('<i class="fa fa-times"></i>', array('data-toggle' => 'tooltip', 'title' => 'Eliminar', 'class' => 'btn btn-effect-ripple btn-xs btn-danger','type' => 'submit')) --}}
                            {{-- Form::close() --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {{ $lots->links() }}
            </div>


            <div class="modal fade" tabindex="-1" role="dialog" id="estado-cuenta">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


            @else
            <p>
                No existen registros de Condominios aún.
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