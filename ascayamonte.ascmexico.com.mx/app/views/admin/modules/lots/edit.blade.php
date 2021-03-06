@extends('admin.layouts.master')

@section('module', 'CONDOMINIOS')
@section('title', 'Condominios')


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
                    <li>{{ link_to_route('admin.lots.index','Condominios') }}</li>
                    <li>Editar</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END Section Header -->


<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <!-- Block -->
        <div class="block">

            <!-- Horizontal Form Title -->
            <div class="block-title">
                <h2>Editar información del Condominio</h2>
                <div class="block-options pull-right">
                    <a href="{{ route('admin.lots.index') }}" class="btn btn-effect-ripple btn-default" data-toggle="tooltip" title="" style="overflow: hidden; position: relative;" data-original-title="Volver al listado"><i class="fa fa-chevron-circle-left"></i> Volver al listado</a>
                </div>
                <ul class="nav nav-tabs" data-toggle="tabs">
                    <li {{ $tab == 1 ? ' class="active" ' : '' }}><a href="#block-tabs-home">Condominio</a></li>
                    <li {{ $tab == 2 ? ' class="active" ' : '' }}><a href="#block-tabs-profile">Dirección</a></li>
                    <li {{ $tab == 3 ? ' class="active" ' : '' }}><a href="#block-tabs-settings">Contactos</a></li>
                </ul>
            </div>
            <!-- END Horizontal Form Title -->


            <!-- Form Content -->
            {{ Form::model($lot, array('method' => 'PATCH', 'route' => array('admin.lots.update', $lot->id), 'class' => 'form-horizontal form-bordered'))  }}

                @include('admin.modules.lots.partials._form')

            {{ Form::close() }}
            <!-- END Form Content -->


        </div>
        <!-- END Block -->

    </div>
</div>
<!-- END Row -->


@stop


@section('javascript')
<!-- ckeditor.js, load it only in the page you would like to use CKEditor (it's a heavy plugin to include it with the others!) -->
{{ HTML::script('assets/admin/js/plugins/ckeditor/ckeditor.js') }}

<!-- Load and execute javascript code used only in this page -->
{{ HTML::script('assets/admin/js/pages/formsComponents.js') }}
<script>$(function(){ FormsComponents.init(); });</script>
@stop