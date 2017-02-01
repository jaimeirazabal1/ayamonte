@extends('admin.layouts.master')


@section('title', 'Novedades')

@section('module', 'NOVEDADES')
@section('content')

<!-- Section Header -->
<div class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <div class="header-section">
                <h1>Novedades</h1>
            </div>
        </div>
        <div class="col-sm-6 hidden-xs">
            <div class="header-section">
                <ul class="breadcrumb breadcrumb-top">
                    <li>{{ link_to_route('admin.dashboard', 'Dashboard') }}</li>
                    <li>Novedades</li>
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
                <h2>Listado de Novedades</h2>
                <small class="label label-sm label-default">{{ $news->getTotal() }} registros</small>
                <div class="block-options pull-right">
                    <a href="{{ route('admin.news.create') }}" class="btn btn-effect-ripple btn-success" data-toggle="tooltip" title="" style="overflow: hidden; position: relative;" data-original-title="Agregar registro"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <!-- END Partial Responsive Title -->

            @if( $news->count() )
            <table class="table table-striped table-borderless table-vcenter">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Visitas</th>
                        <th class="hidden-sm hidden-xs">Estatus</th>
                        <th style="width: 80px;" class="text-center"><i class="fa fa-flash"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($news as $new)
                    <tr>
                        <td>
                            <strong>{{ link_to_route('admin.news.edit', $new->title, $new->id)  }}</strong><br />
                        </td>
                        <td>
                            <small class="label label-default">{{ $new->views }}</small>
                        </td>
                        <td class="hidden-sm hidden-xs">
                            @if($new->status)
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-default">Inactivo</span>
                            @endif
                        </td>
                        <td width="100px" class="text-center">
                            <button class="btn btn-default btn-xs" data-title="Contenido" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{ nl2br($new->description) }}" data-html="true"><i class="fa fa-search"></i></button>
                            <a href="{{ route('admin.news.edit', $new->id) }}" data-toggle="tooltip" title="Editar" class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {{ $news->links() }}
            </div>

            @else
            <p>
                <strong>No</strong> existen registros de Novedades aún.
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