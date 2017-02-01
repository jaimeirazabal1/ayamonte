@extends('admin.layouts.master')

@section('module', 'PAGOS')
@section('title', 'Pagos')


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
                        <li>{{ link_to_route('admin.payments.index','Pagos') }}</li>
                        <li>Generar pago</li>
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
                    <h2>Información del pago</h2>
                    <div class="block-options pull-right">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-effect-ripple btn-default" data-toggle="tooltip" title="" style="overflow: hidden; position: relative;" data-original-title="Volver al listado"><i class="fa fa-chevron-circle-left"></i> Volver al listado</a>
                    </div>
                </div>
                <!-- END Horizontal Form Title -->


                <!-- Form Content -->
                {{ Form::open(array('method' => 'POST', 'route' => 'admin.payments.store', 'class' => 'form-horizontal form-bordered'))  }}

                @include('admin.modules.payments.partials._form')

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
    <script>
        $(function(){
            FormsComponents.init();

            $('#lot_id').on('change', function(){
                var $this = $(this),
                        val = $this.val(),
                        url = window.location.href;

                window.location.href = ('/admin/payments/create?lot=' + val);
            });

            var selectable = $( '.selectable' );

            selectable.click( function( event ) {
                var element = $( this );
                var amount = null;
                if (!$(this).hasClass('row-active')) {
                    $(this).removeClass('row-active');
                }
                // selectable.removeClass( 'row-active' );
                amount = 0.00;
                element.addClass( 'row-active' );
                $('.row-active').each(function(){
                    thisamount = $(this).find(".amount").html().replace( '$', '' );
                    thisamount = thisamount.replace( ',', '' );
                    amount = amount + parseFloat(thisamount);

                })
                $( '#amount' ).val( amount.toFixed(2) );
            } );
        });
    </script>


@stop