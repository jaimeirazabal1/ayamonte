{{ Form::open(array('method' => 'POST', 'route' => array('admin.lots.contacts.store', $lot->id), 'class' => 'form-horizontal', 'data-contacts-form-data' => '')) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class="modal-title">Contactos - Lote <small class="label label-default">{{ $lot->official_number }}</small></h3>
</div>
<div class="modal-body">


    {{-- ---}}

    {{-- Tipo -  SELECT2 --}}
    <div class="form-group {{ $errors->first('type') ? 'has-error' : '' }}">

        {{ Form::label('type', 'Tipo de contacto', array('class' => 'col-md-3 control-label'))  }}
        <div class="col-md-9">
            {{ Form::select('type', array('owner' => 'Propietario', 'guest' => 'Inquilino', 'assistant' => 'Asistente'), null, array('class' => 'select-select2', 'placeholder' => '', 'data-placeholder' => 'Zona', 'style' => 'width: 100%;', 'id' => 'type')) }}

            @if( $errors->first('type'))
                <span class="help-block">{{ $errors->first('type')  }}</span>
            @endif

        </div>
    </div>


    {{-- Nombre -  TEXT --}}
    <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">

        {{ Form::label('name', 'Nombre', array('class' => 'col-md-3 control-label'))  }}
        <div class="col-md-9">
            {{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => '', 'id' => 'name')) }}
            @if( $errors->first('name'))
                <p class="help-block">{{ $errors->first('name')  }}</p>
            @endif
        </div>
    </div>


    {{-- Correo electrónico -  TEXTAREA --}}
    <div class="form-group {{ $errors->first('email')? 'has-error' : '' }}">

        {{ Form::label('email', 'Correo electrónico', array('class' => 'col-md-3 control-label'))  }}
        <div class="col-md-9">
            {{ Form::textarea('email', null, array('class' => 'form-control', 'rows' => 7,  'placeholder' => '', 'id' => 'email')) }}
            @if( $errors->first('email'))
                <span class="help-block">{{ $errors->first('email')  }}</span>
            @endif
        </div>
    </div>



    {{-- Teléfono -  TEXTAREA --}}
    <div class="form-group {{ $errors->first('phone')? 'has-error' : '' }}">

        {{ Form::label('phone', 'Teléfono', array('class' => 'col-md-3 control-label'))  }}
        <div class="col-md-9">
            {{ Form::textarea('phone', null, array('class' => 'form-control', 'rows' => 7,  'placeholder' => '', 'id' => 'phone')) }}
            @if( $errors->first('phone'))
                <span class="help-block">{{ $errors->first('phone')  }}</span>
            @endif
        </div>
    </div>



    {{-- --}}

</div>
<div class="modal-footer">
    <button data-contacts-form type="submit" class="btn btn-effect-ripple btn-success">Guardar</button>
    <button type="button" class="btn btn-effect-ripple btn-default" data-dismiss="modal">Cerrar</button>
</div>
{{ Form::close() }}

<script>
    $(function()
    {
        $('[data-contacts-form]').on('click', function(e)
        {
            e.preventDefault();

            var $form = $('[data-contacts-form-data]');

            var action = $form.prop('action');

            var name = $('#name').val() || null;
            var email = $('#email').val() || null;
            var phone = $('#phone').val() || null;

            if ( name && email && phone)
            {
                $form.submit();
            }
            else
            {
                alert('Por favor verifique que la información sea correcta.');
            }

        });
    });
</script>






