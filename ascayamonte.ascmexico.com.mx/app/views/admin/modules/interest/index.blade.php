@extends('admin.layouts.master')

@section('module', 'INTERESES')
@section('title', 'Intereses')


@section('content')

<!-- Section Header -->
<div class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <div class="header-section">
                <h1>Intereses</h1>
            </div>
        </div>
        <div class="col-sm-6 hidden-xs">
            <div class="header-section">
                <ul class="breadcrumb breadcrumb-top">
                    <li>{{ link_to_route('admin.dashboard', 'Dashboard') }}</li>
                    <li>Intereses</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END Section Header -->

<!-- Tables Row -->
<div class="row">
    <div class="col-lg-8">
        <!-- Partial Responsive Block -->
        <div class="block">
            <!-- Partial Responsive Title -->
            <div class="block-title">
                <h2>Listado de Intereses</h2>
                <small class="label label-sm label-default">{{ $interests->getTotal() }} registros</small>
                <div class="block-options pull-right">
                    <a href="{{ route('admin.interest.create') }}" class="btn btn-effect-ripple btn-success" data-toggle="tooltip" title="" style="overflow: hidden; position: relative;" data-original-title="Agregar registro"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <!-- END Partial Responsive Title -->

            @if( $interests->count() )
            <table class="table table-striped table-borderless table-vcenter">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th class="hidden-xs"></th>
                        <th class="hidden-sm hidden-xs">Estatus</th>
                        <th style="width: 80px;" class="text-center"><i class="fa fa-flash"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($interests as $interest)
                    <tr>
                        <td><strong>{{ link_to_route('admin.interest.edit', $interest->name, $interest->id)  }}</strong></td>
                        <td class="hidden-xs"></td>
                        <td class="hidden-sm hidden-xs">
                            @if($interest->is_active)
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-default">Inactivo</span>
                            @endif
                        </td>
                        <td width="100px" class="text-center">
                            <a href="{{ route('admin.interest.edit', $interest->id) }}" data-toggle="tooltip" title="Editar" class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                            {{--{{ Form::open(array('route' => array('admin.interest.destroy', $interest->id), 'method' => 'DELETE', 'class' => 'form-inline','data-delete-confirmation' => '')) }}--}}
                                    {{--{{ Form::button('<i class="fa fa-times"></i>', array('data-toggle' => 'tooltip', 'title' => 'Eliminar', 'class' => 'btn btn-effect-ripple btn-xs btn-danger','type' => 'submit')) }}--}}
                            {{--{{ Form::close() }}--}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {{ $interests->links() }}
            </div>

            @else
            <p>
                <strong>No</strong> existen registros de Intereses aún.
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