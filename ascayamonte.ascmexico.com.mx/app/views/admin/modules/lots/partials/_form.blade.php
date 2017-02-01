

    <!-- Tabs Content -->
    <div class="tab-content">
        <div class="tab-pane  {{ $tab == 1 ? 'active' : '' }}" id="block-tabs-home">

            <!-- Form Content -->
            {{ Form::open(array('method' => 'POST', 'route' => 'admin.lots.store', 'class' => 'form-horizontal form-bordered'))  }}

                {{-- Número Oficial -  TEXT --}}
                <div class="form-group {{ $errors->first('official_number') ? 'has-error' : '' }}">

                    {{ Form::label('official_number', 'Número oficial', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-3">
                        {{ Form::text('official_number', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'official_number')) }}
                        @if( $errors->first('official_number'))
                            <p class="help-block">{{ $errors->first('official_number')  }}</p>
                        @endif
                    </div>
                </div>


                {{-- Plaza -  SELECT2 --}}
                <div class="form-group {{ $errors->first('square_id') ? 'has-error' : '' }}">

                    {{ Form::label('square_id', 'Plaza', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-5">
                        {{ Form::select('square_id', $squares, null, array('class' => 'select-select2', 'placeholder' => '', 'data-placeholder' => 'Zona', 'style' => 'width: 100%;', 'id' => 'square_id')) }}

                        @if( $errors->first('square_id'))
                            <span class="help-block">{{ $errors->first('square_id')  }}</span>
                        @endif

                    </div>
                </div>


                {{-- Lote -  TEXT --}}
                <div class="form-group {{ $errors->first('lot') ? 'has-error' : '' }}">

                    {{ Form::label('lot', 'Lote', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-3">
                        {{ Form::text('lot', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'lot')) }}
                        @if( $errors->first('lot'))
                            <p class="help-block">{{ $errors->first('lot')  }}</p>
                        @endif
                    </div>
                </div>


                {{-- Referencia -  TEXT --}}
                <div class="form-group {{ $errors->first('reference') ? 'has-error' : '' }}">

                    {{ Form::label('reference', 'Referencia', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('reference', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'reference')) }}
                        @if( $errors->first('reference'))
                            <p class="help-block">{{ $errors->first('reference')  }}</p>
                        @endif
                    </div>
                </div>



                {{-- Propietario -  TEXT --}}
                <div class="form-group {{ $errors->first('owner') ? 'has-error' : '' }}">

                    {{ Form::label('owner', 'Propietario', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-9">
                        {{ Form::text('owner', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'owner')) }}
                        @if( $errors->first('owner'))
                            <p class="help-block">{{ $errors->first('owner')  }}</p>
                        @endif
                    </div>
                </div>



                {{-- M2 -  TEXT --}}
                <div class="form-group {{ $errors->first('m2') ? 'has-error' : '' }}">

                    {{ Form::label('m2', 'M2', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('m2', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'm2', (empty($lot->m2) ? 'data-disabled' : 'disabled') => '')) }}
                        @if( $errors->first('m2'))
                            <p class="help-block">{{ $errors->first('m2')  }}</p>
                        @endif

                        <p class="help-block">Metros cuadrados</p>
                    </div>
                </div>



                {{-- Cuenta -  TEXT --}}
                <div class="form-group {{ $errors->first('account_number') ? 'has-error' : '' }}">

                    {{ Form::label('account_number', 'Cuenta', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('account_number', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'account_number')) }}
                        @if( $errors->first('account_number'))
                            <p class="help-block">{{ $errors->first('account_number')  }}</p>
                        @endif
                    </div>
                </div>


                {{-- Clave catastral -  TEXT --}}
                <div class="form-group {{ $errors->first('cadastral_key') ? 'has-error' : '' }}">

                    {{ Form::label('cadastral_key', 'Clave catastral', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('cadastral_key', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'cadastral_key')) }}
                        @if( $errors->first('cadastral_key'))
                            <p class="help-block">{{ $errors->first('cadastral_key')  }}</p>
                        @endif
                    </div>
                </div>
                
                @if ( empty($lot))
                {{-- Fecha de compra - DATE PICKER --}}
                <div class="form-group  {{ $errors->first('purchase_date') ? 'has-error' : '' }}">
                    {{ Form::label('purchase_date', 'Fecha de compra', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-5">
                        {{ Form::text('purchase_date', empty($lot) ? date('Y-m-d') : $lot->purchase_date->format('Y-m-d'), array('class' => 'form-control input-datepicker', 'placeholder' => 'yyyy-mm-dd', 'id' => 'purchase_date', 'data-date-format' => 'yyyy-mm-dd', 'readonly' => true)) }}
                        @if( $errors->first('purchase_date'))
                            <p class="help-block">{{ $errors->first('purchase_date')  }}</p>
                        @endif
                        <p class="help-block">
                            El estado de cuenta será generado un mes posterior al de la fecha de compra.<br />
                            Ej: Si la fecha de compra es el 1/Oct/2011 el estado de cuenta se generará a partir de 1/Nov/2011.
                        </p>
                    </div>
                </div>
                @else
                    {{-- Fecha de compra -  TEXT --}}
                    <div class="form-group">
                        
                        {{ Form::label('purchase_date', 'Fecha de compra', array('class' => 'col-md-3 control-label'))  }}
                        <div class="col-md-8">
                            <p class="form-control-static">{{ $lot->purchase_date->format('Y-m-d') }}</p>
                            <p class="help-block">
                                El estado de cuenta será generado un mes posterior al de la fecha de compra.<br />
                                Ej: Si la fecha de compra es el 1/Oct/2011 el estado de cuenta se generará a partir del 1/Nov/2011.
                            </p>
                        </div>
                    </div>
                    
                @endif


                {{-- FORM ACTIONS --}}
                <div class="form-group form-actions">
                    <div class="col-md-9 col-md-offset-3">
                        {{ Form::submit('Guardar', array('class' => 'btn btn-effect-ripple btn-primary')) }}
                        {{ Form::reset('Limpiar formulario', array('class' => 'btn btn-effect-ripple btn-danger')) }}
                    </div>
                </div>

            {{ Form::close() }}

        </div>

        @if ( ! empty($lot))
        <div class="tab-pane {{ $tab == 2 ? 'active' : '' }}" id="block-tabs-profile">


            <!-- Form Content -->
            {{ Form::open(array('method' => 'POST', 'route' => array('admin.lots.address.create-update', $lot->id), 'class' => 'form-horizontal form-bordered'))  }}


                {{-- Calle -  TEXT --}}
                <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">

                    {{ Form::label('address', 'Calle y número', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('address', empty($lot->address) ? null : $lot->address->address, array('class' => 'form-control', 'placeholder' => '', 'id' => 'address')) }}
                        @if( $errors->first('address'))
                            <p class="help-block">{{ $errors->first('address')  }}</p>
                        @endif
                    </div>
                </div>


                {{-- Colonia -  TEXT --}}
                <div class="form-group {{ $errors->first('neighborhood') ? 'has-error' : '' }}">

                    {{ Form::label('neighborhood', 'Colonia', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('neighborhood', empty($lot->address) ? null : $lot->address->neighborhood, array('class' => 'form-control', 'placeholder' => '', 'id' => 'neighborhood')) }}
                        @if( $errors->first('neighborhood'))
                            <p class="help-block">{{ $errors->first('neighborhood')  }}</p>
                        @endif
                    </div>
                </div>


                {{-- Código postal -  TEXT --}}
                <div class="form-group {{ $errors->first('zipcode') ? 'has-error' : '' }}">

                    {{ Form::label('zipcode', 'Código postal', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-3">
                        {{ Form::text('zipcode', empty($lot->address) ? null : $lot->address->zipcode, array('class' => 'form-control', 'placeholder' => '', 'id' => 'zipcode')) }}
                        @if( $errors->first('zipcode'))
                            <p class="help-block">{{ $errors->first('zipcode')  }}</p>
                        @endif
                    </div>
                </div>



                {{-- Municipio -  TEXT --}}
                <div class="form-group {{ $errors->first('city') ? 'has-error' : '' }}">

                    {{ Form::label('city', 'Municipio', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('city', empty($lot->address) ? null : $lot->address->city, array('class' => 'form-control', 'placeholder' => '', 'id' => 'city')) }}
                        @if( $errors->first('city'))
                            <p class="help-block">{{ $errors->first('city')  }}</p>
                        @endif
                    </div>
                </div>


                {{-- Estado -  TEXT --}}
                <div class="form-group {{ $errors->first('state') ? 'has-error' : '' }}">

                    {{ Form::label('state', 'Estado', array('class' => 'col-md-3 control-label'))  }}
                    <div class="col-md-6">
                        {{ Form::text('state', empty($lot->address) ? null : $lot->address->state, array('class' => 'form-control', 'placeholder' => '', 'id' => 'state')) }}
                        @if( $errors->first('state'))
                            <p class="help-block">{{ $errors->first('state')  }}</p>
                        @endif
                    </div>
                </div>


                {{ Form::hidden('lot_id', empty($lot) ? null : $lot->id) }}

                {{-- FORM ACTIONS --}}
                <div class="form-group form-actions">
                    <div class="col-md-9 col-md-offset-3">
                        {{ Form::submit('Guardar', array('class' => 'btn btn-effect-ripple btn-primary')) }}
                        {{ Form::reset('Limpiar formulario', array('class' => 'btn btn-effect-ripple btn-danger')) }}
                    </div>
                </div>

            {{ Form::close() }}

        </div>
        <div class="tab-pane {{ $tab == 3 ? 'active' : '' }}" id="block-tabs-settings">

            @if ( count($lot->contacts))
                <table class="table table-striped table-borderless table-vcenter" style="width: 600px;">
                    <thead>
                    <tr>
                        <th>Contactos</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Teléfono</th>
                        <!--th class="hidden-xs"></th>
                        <th class="hidden-sm hidden-xs">Estatus</th>
                        <th style="width: 80px;" class="text-center"><i class="fa fa-flash"></i></th-->
                        <th>
                            <a data-target="#modal-master" href="{{ route('admin.lots.contacts.create', $lot->id) }}"  data-toggle="modal" class="btn btn-success btn-xs">Agregar <i class="fa fa-plus"></i></a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lot->contacts as $contact)
                        <tr>
                            <td>
                                <strong>{{ $contact->name }}</strong><br />
                                <small class="text-muted">
                                    @if ($contact->type == 'owner')
                                        Propietario
                                    @elseif ( $contact->type == 'guest')
                                        Inquilino
                                    @else
                                        Asistente
                                    @endif
                                </small>
                            </td>
                            <td class="hidden-xs text-center">
                                {{ nl2br($contact->email) }}
                            </td>
                            <td class="hidden-xs text-center">
                                {{ nl2br($contact->phone) }}

                            </td>
                            <td width="100px" class="text-center">
                                {{ Form::open(array('route' => array('admin.lots.contacts.destroy', $lot->id, $contact->id), 'method' => 'DELETE', 'class' => 'form-inline','data-destroy-resource' => '')) }}
                                {{ Form::button('<i class="fa fa-times"></i>', array('data-toggle' => 'tooltip', 'title' => 'Eliminar', 'class' => 'btn btn-effect-ripple btn-xs btn-danger','type' => 'submit')) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @else
                <p class="text-muted" style="padding: 20px;">
                    No existen contactos registrados. <a data-target="#modal-master" href="{{ route('admin.lots.contacts.create', $lot->id) }}"  data-toggle="modal" class="btn btn-success btn-xs">Agregar <i class="fa fa-plus"></i></a>
                </p>
            @endif



                <!-- MODAL -->
                @include('admin.layouts.modal')
                <!-- END MODAL -->



        </div>
        @endif
    </div>
    <!-- END Tabs Content -->







