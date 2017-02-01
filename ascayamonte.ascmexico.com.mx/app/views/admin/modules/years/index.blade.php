@extends('admin.layouts.master')

@section('module', 'TARIFAS')
@section('title', 'Tarifas')


@section('content')

<!-- Section Header -->
<div class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <div class="header-section">
                <h1>Tarifas</h1>
            </div>
        </div>
        <div class="col-sm-6 hidden-xs">
            <div class="header-section">
                <ul class="breadcrumb breadcrumb-top">
                    <li>{{ link_to_route('admin.dashboard', 'Dashboard') }}</li>
                    <li>Tarifas</li>
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
                <h2>Listado</h2>
                <small class="label label-sm label-default">{{ $years->getTotal() }} registros</small>
                <div class="block-options pull-right">
                    <a href="{{ route('admin.years.create') }}" class="btn btn-effect-ripple btn-success" data-toggle="tooltip" title="" style="overflow: hidden; position: relative;" data-original-title="Agregar registro"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <!-- END Partial Responsive Title -->

            @if( $years->count() )
            <table class="table table-striped table-borderless table-vcenter">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cuotas</th>
                        <th style="width: 80px;" class="text-center"><i class="fa fa-flash"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($years as $year)
                    <tr>
                        <td><strong>{{ link_to_route('admin.years.edit', $year->year, $year->id)  }}</strong></td>
                        <td>
                            <?php $fees1_content = '<small>'; ?>

                            @foreach($year->feesordinaries as $fee)
                                <?php $fees1_content .= ($fee->month->name . ' - $' . convertIntegerToMoney($fee->rate_first_days) ); ?>
                                <?php $fees1_content .= (', $' . convertIntegerToMoney($fee->rate). '<br />'); ?>
                            @endforeach

                                <?php $fees1_content .= '</small>'; ?>

                            <div><a href="#" class="btn btn-xs btn-effect-ripple btn-info" data-toggle="popover" data-trigger="hover" data-placement="right" data-title="Info" data-html="true" data-content="{{ $fees1_content }}">Cuota ordinaria mensual</a></div>


                                <?php $fees1_year_content = '<small>'; ?>

                            @foreach($year->feesordinariesyearly as $fee)
                                <?php $fees1_year_content .= ($fee->month->name . ' - $' . convertIntegerToMoney($fee->rate)); ?>
                                <?php $fees1_year_content .= (', ' . $fee->discount_yearly . '%<br />'); ?>
                            @endforeach

                            <?php $fees1_content .= '</small>'; ?>

                            <div><a href="#" class="btn btn-xs btn-effect-ripple btn-info" data-toggle="popover" data-trigger="hover" data-placement="right" data-title="Info" data-html="true" data-content="{{ $fees1_year_content }}">Cuota ordinaria anual</a></div>


                                <?php $fees2_content = '<small>';

                                $next_year = Year::where('year', ($year->year + 1))->first();

                                if ( ! empty($next_year))
                                {

                                ?>

                                {{--@foreach($year->feesextraordinaryspecialwork as $fee)--}}
                                    <?php //$fees2_content .= ($fee->month->name . ' - $' . convertIntegerToMoney($fee->rate) . ' - ' . convertIntegerToMoney($fee->mount) . '<br />'); ?>
                                {{--@endforeach--}}

                                <?php
                                    $diciembre = Month::find(12);
                                    $diciembreFee = FeesExtraordinarySpecialWork::where('month_id', $diciembre->id)->where('year_id', $year->id)->first();

                                    $enero = Month::find(1);
                                    $eneroFee = FeesExtraordinarySpecialWork::where('month_id', $enero->id)->where('year_id', $next_year->id)->first();
                                    $febrero = Month::find(2);
                                    $febreroFee = FeesExtraordinarySpecialWork::where('month_id', $febrero->id)->where('year_id', $next_year->id)->first();

                                    $marzo = Month::find(3);
                                    $marzoFee = FeesExtraordinarySpecialWork::where('month_id', $marzo->id)->where('year_id', $next_year->id)->first();

                                    $octubre = Month::find(10);
                                    $octubreFee = FeesExtraordinarySpecialWork::where('month_id', $octubre->id)->where('year_id', $next_year->id)->first();

                                    $noviembre = Month::find(11);
                                    $noviembreFee = FeesExtraordinarySpecialWork::where('month_id', $noviembre->id)->where('year_id', $next_year->id)->first();

                                ?>

                                <?php $fees2_content .= ($diciembre->name . ' - $' . convertIntegerToMoney($diciembreFee->mount) . '<br />'); ?>
                                <?php $fees2_content .= ($enero->name . ' - $' . convertIntegerToMoney($eneroFee->mount) . '<br />'); ?>
                                <?php $fees2_content .= ($febrero->name . ' - $' . convertIntegerToMoney($febreroFee->mount) . '<br />'); ?>
                                <?php $fees2_content .= ($marzo->name . ' - $' . convertIntegerToMoney($marzoFee->mount) . '<br />'); ?>
                                <?php $fees2_content .= ($octubre->name . ' - ' . $octubreFee->percent . '%<br />'); ?>
                                <?php $fees2_content .= ($noviembre->name . ' - ' . $noviembreFee->percent . '%<br />');

                                    }
                                ?>


                                <?php $fees2_content .= '</small>'; ?>
                            <div><a href="#" class="btn btn-xs btn-effect-ripple btn-info" data-toggle="popover" data-trigger="hover" data-placement="right" data-title="Info" data-html="true" data-content="{{ $fees2_content }}">Cuota Extraordinaria de Obra</a></div>

                                <?php $fees3_content = '<small>'; ?>

                                @foreach($year->feesextraordinariesreserves as $fee)
                                    <?php $fees3_content .= ($fee->month->name . ' - $' . convertIntegerToMoney($fee->rate) . '<br />'); ?>
                                @endforeach

                                <?php $fees3_content .= '</small>'; ?>
                            <div><a href="#" class="btn btn-xs btn-effect-ripple btn-info" data-toggle="popover" data-trigger="hover" data-placement="right" data-title="Info" data-html="true" data-content="{{ $fees3_content }}">Cuota Especial de Reserva</a></div>

                                <?php $fees4_content = '<small>'; ?>

                                @foreach($year->feesdebs2010 as $fee)
                                    <?php $fees4_content .= ($fee->month->name . ' - $' . convertIntegerToMoney($fee->rate) . '<br />'); ?>
                                @endforeach

                                <?php $fees4_content .= '</small>'; ?>
                            <div><a href="#" class="btn btn-xs btn-effect-ripple btn-info" data-toggle="popover" data-trigger="hover" data-placement="right" data-title="Info" data-html="true" data-content="{{ $fees4_content }}">Cuota Especial Adeudo 2010</a></div>

                                <?php $fees5_content = '<small>'; ?>

                                @foreach($year->interests as $fee)
                                    <?php $fees5_content .= ($fee->month->name . ' - %' . $fee->rate . '<br />'); ?>
                                @endforeach

                                <?php $fees5_content .= '</small>'; ?>
                            <div><a href="#" class="btn btn-xs btn-effect-ripple btn-info" data-toggle="popover" data-trigger="hover" data-placement="right" data-title="Info" data-html="true" data-content="{{ $fees5_content }}">Intereses</a></div>
                        </td>
                        <td width="100px" class="text-center">
                            <a href="{{ route('admin.years.edit', $year->id) }}" data-toggle="tooltip" title="Editar" class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                            {{--{{ Form::open(array('route' => array('admin.years.destroy', $year->id), 'method' => 'DELETE', 'class' => 'form-inline','data-delete-confirmation' => '')) }}--}}
                                    {{--{{ Form::button('<i class="fa fa-times"></i>', array('data-toggle' => 'tooltip', 'title' => 'Eliminar', 'class' => 'btn btn-effect-ripple btn-xs btn-danger','type' => 'submit')) }}--}}
                            {{--{{ Form::close() }}--}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center">
                {{ $years->links() }}
            </div>

            @else
            <p>
                <strong>No</strong> existen registros de Tarifas aún.
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