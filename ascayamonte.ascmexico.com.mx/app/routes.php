<?php

// TODO : Cronjob para actualizar los años de las cuotas automaticamente
// TODO : Generar los estados de cuenta al principio del mes y enviarlo por correo a cada propietario del condominio
// TODO : Preguntar en los meses de enero, febreo, marzo, y abril si la cuota de mantenimiento mensual de pagara anual o mensual


/*
|--------------------------------------------------------------------------
| Site Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', function()
{
    return Redirect::route('admin.dashboard');
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'api'], function()
{

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

/**
 * Genera un estado de cuenta de prueba
 */
/*
Route::get('pdf', function()
{
    $pdf = PDF::loadView('pdf.account-status');
    return $pdf->download('estado-de-cuenta.pdf');
});
*/
/*
Route::get('email', function(){
    return View::make('emails.estado-cuenta');
});
*/
/*
Route::get('test-errors', function(){
    Event::fire('lot.monthly.maintenance.errors', [
        'errors' => [
            Lot::find(41)
        ],
        'year' => 2016,
        'month' => 1
    ]);
});
*/
/*
Route::get('test-lots', function(){
    $lots = Lot::with(['contacts' => function($query)
    {
        $query->where('type', 'owner');
    }])->get();

    return $lots;
});*/

/*
Route::get('mail', function(){
    $name    = 'José Martín Pérez';
    $email = 'diego.h.glez@gmail.com';

    $data['email'] = $email;
    $data['name']  = $name;

    $month = '5';
    $year = 2016;

    $lot = new StdClass;

    $lot->id = 1;

    $data['month'] = $month;
    $data['year']  = $year;
    $data['lot']   = $lot;

    \Mail::send('emails.estado-cuenta', $data, function($message) use ($email, $name, $month, $year)
    {
        $message->to($email, $name)->subject('Estado de cuenta - Ayamonte.mx');
    });
});*/


/*Route::get('download/{id}', function($id){

    $lot = Lot::with(['contacts' => function($query)
    {
        $query->where('type', 'owner');
    }])->find($id);

    $transactions = Transaction::with('year')->byLot($id)->notPaid()->oldest()->get();

    $pdf = PDF::loadView('pdf.account-status', compact('lot', 'transactions'));

    return $pdf->download('estado-de-cuenta.pdf');
});*/
Route::get('download/{id}', ['as' => 'lots.download', 'uses' => 'AdminLotsController@download']);


Route::get('admin/login', ['as' => 'admin.sessions.create', 'uses' => 'AdminSessionsController@create']);
Route::post('admin/login', ['as' => 'admin.sessions.store', 'uses' => 'AdminSessionsController@store']);
Route::get('admin/password-recovery', ['as' => 'admin.sessions.password-recovery', 'uses' => 'AdminSessionsController@passwordRecovery']);
Route::post('admin/password-recovery', ['as' => 'admin.sessions.password-recovery.post', 'uses' => 'AdminSessionsController@passwordRecoverySend']);
Route::get('admin/password-reset/{email}/{token}', ['as' => 'admin.sessions.password-reset', 'uses' => 'AdminSessionsController@passwordReset']);
Route::post('admin/password-reset/{email}/{token}', ['as' => 'admin.sessions.password-reset.post', 'uses' => 'AdminSessionsController@passwordResetSend']);
Route::get('admin/logout', ['as' => 'admin.sessions.destroy', 'uses' => 'AdminSessionsController@destroy']);


#
Route::group(['before' => 'auth.admin.sentry', 'prefix' => 'admin'], function()
{
    Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'AdminDasboardController@index']);
    Route::get('lots/{id}/payments', ['as' => 'admin.lots.payments', 'uses' => 'AdminLotsController@payments']); # LOTES
    Route::get('lots/{id}/download', ['as' => 'admin.lots.download', 'uses' => 'AdminLotsController@download']); # LOTES
    Route::get('lots/{id}/send', ['as' => 'admin.lots.send-by-email', 'uses' => 'AdminLotsController@sendByEmail']); # LOTES
    Route::resource('lots', 'AdminLotsController', ['except' => ['destroy']]); # LOTES
    Route::post('lots/{lot_id}/address/{id?}', ['as' => 'admin.lots.address.create-update', 'uses' => 'AdminLotsAddressController@createOrUpdate']);
    Route::resource('lots.contacts', 'AdminLotsContactsController', ['except' => ['index', 'edit', 'show', 'update']]);
    Route::resource('users', 'AdminUsersController', ['except' => ['show', 'destroy']]);
    Route::resource('news', 'AdminNewsController', ['except' => ['show', 'destroy']]);
    Route::resource('years', 'AdminYearsController', ['except' => ['show', 'destroy']]);
    Route::get('payments/{id}/download', ['as' => 'admin.payments.download', 'uses' => 'AdminPaymentsController@download']); # LOTES
    /*Route::get('payments/{id}/download',
        function(){
            return 'Recibo de pago - pendiente formato';
        }); # LOTES*/
    Route::resource('payments', 'AdminPaymentsController', ['except' => ['destroy', 'update', 'edit']]);
});


/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
|
*/

// TODO : Validar que si se vuelve a generar el estado de cuenta desde otro punto no se dupliquen los ya generados

// TODO : al programar el cronjob ejecutarlo 2 veces por si sucede algun error, volver a regenerar los estados de cuenta fallidos, ademas de poder generarlo manualmente

// TODO : antes de generar el estado de cuenta verificar si no hay errores para el condominio actual, si hay errores entonces no lo calcula hasta que este al corriente


/**
 * Genera el mantenimiento mensual manualmente
 */
/*
Route::get('monthly-maintenance', function()
{
    $errors = [];

    // Obtiene todos los condominios con sus contactos del tipo propietario
    $lots = Lot::with(['contacts' => function($query)
    {
        $query->where('type', 'owner');
    }])->get();

    $purchase_date = \Carbon\Carbon::createFromFormat('Y-m-d', '2011-11-01');
    $current_date  = \Carbon\Carbon::createFromFormat('Y-m-d', '2015-06-01');

    while($purchase_date->timestamp <= $current_date->timestamp)
    {
        $year  = $purchase_date->year;
        $month = $purchase_date->month;


        # Recorre todos los condominios
        foreach($lots as $lot)
        {
            DB::beginTransaction();

            try
            {
                # Actualiza las deudas anteriores
                Event::fire('lot.update.debts', [
                    'lot_id' => $lot->id
                ]);

                # Genera la cuota de mantenimiento mensual
                Event::fire('lot.monthly.maintenance', [
                    'lot_id' => $lot->id,
                    'year_id' => $year,
                    'month_id' => $month
                ]);

                Event::fire('lot.generate.exrtaordinary.reserve', [
                    'lot_id' => $lot->id,
                    'year_id' => $year,
                    'month_id' => $month
                ]);

                Event::fire('lot.generate.special.work', [
                    'lot_id' => $lot->id,
                    'year_id' => $year,
                    'month_id' => $month
                ]);

                Event::fire('lot.generate.exrtaordinary.debts2010', [
                    'lot_id' => $lot->id,
                    'year_id' => $year,
                    'month_id' => $month
                ]);

                DB::commit();
            }
            catch(\Exception $e) // Ocurrió un error al generar la cuota mensual para el condominio actual
            {
                DB::rollback();

                AuditCronjobBalance::create([
                    'lot_id' => $lot->id,
                    'month' => $month,
                    'year' => $year
                ]);

                $errors[] = $lot;
                // TODO : enviar al administrador del sistema los errores de los estados de cuenta que no se pudieron generar
                // TODO : - para generarlos manualmente, consultar el log de audit_cronjob_balance para extraer los errores
                // TODO : - y enviar por correo electrónico
                die($e->getMessage());
            }
        }

        $purchase_date->addMonth();
    }

    // Notifica los errores que ocurrieron al generar las cuotas mensuales
    if(count($errors))
    {
        Event::fire('lot.monthly.maintenance.errors', [
            'lot_id' => $lot->id,
            'year_id' => $year,
            'month_id' => $month
        ]);
    }

});
*/

/**
 * Importa el archivo de excel, genera las cuotas desde la fecha indicada
 */
/*
Route::get('load', function(){

    $file = public_path() . '/import/ayamonte-records.xlsx';

    Excel::load($file, function($reader) {

        // Getting all results
        $results = $reader->get();

        foreach($results as $row)
        {
            $purchase_date = ($row->mantenimiento_inicio == '-') ? \Carbon\Carbon::createFromFormat('Y/m', '2016/01') : \Carbon\Carbon::createFromFormat('Y/m', $row->mantenimiento_inicio);
            $current_date  = ($row->mantenimiento_fin == '-') ? \Carbon\Carbon::createFromFormat('Y/m', '2016/12') :  \Carbon\Carbon::createFromFormat('Y/m', $row->mantenimiento_fin);

            $square = Square::byName($row->plaza)->first();

            # Crea el lote
            $lot = Lot::create([
                'reference' => rand(1, 900000),
                'official_number' => $row->numero_oficial,
                'm2' => $row->m2,
                'cadastral_key' => str_random(10),
                'owner' => $row->propietario,
                'lot' => $row->lote,
                'account_number' => str_random(10),
                'square_id' => $square->id,
                'purchase_date' => $purchase_date->toDateTimeString()
            ]);

            $tiene_adeudo_2010          = (strtolower($row->adeudo_2010) == 'si') ? true : false;
            $tiene_adeudo_reserva       = (strtolower($row->adeudo_reserva) == 'si') ? true : false;
            $tiene_adeudo_especial_obra = (strtolower($row->adeudo_especial_obra) == 'si') ? true : false;
            $pago_anualidad             = (strtolower($row->anualidad) == 'si') ? true : false;


            if ($tiene_adeudo_reserva)
            {
                Event::fire('lot.created.reserve', ['lot_id' => $lot->id]);
            }

            if ( $tiene_adeudo_especial_obra)
            {
                Event::fire('lot.created.specialwork', ['lot_id' => $lot->id]);
            }

            if ($tiene_adeudo_2010)
            {
                Event::fire('lot.created.debs2010', ['lot_id' => $lot->id]);
            }

            while($purchase_date->timestamp <= $current_date->timestamp)
            {
                $year  = $purchase_date->year;
                $month = $purchase_date->month;

                DB::beginTransaction();

                try
                {
                    # Actualiza las deudas anteriores
                    Event::fire('lot.update.debts', [
                        'lot_id' => $lot->id
                    ]);

                    # Genera la cuota de mantenimiento mensual
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year,
                        'month_id' => $month
                    ]);

                    if ( ! $pago_anualidad)
                    {
                        Event::fire('lot.generate.exrtaordinary.reserve', [
                            'lot_id' => $lot->id,
                            'year_id' => $year,
                            'month_id' => $month
                        ]);

                        Event::fire('lot.generate.special.work', [
                            'lot_id' => $lot->id,
                            'year_id' => $year,
                            'month_id' => $month
                        ]);

                        Event::fire('lot.generate.exrtaordinary.debts2010', [
                            'lot_id' => $lot->id,
                            'year_id' => $year,
                            'month_id' => $month
                        ]);
                    }


                    DB::commit();
                }
                catch(\Exception $e) // Ocurrió un error al generar la cuota mensual para el condominio actual
                {
                    DB::rollback();

                    AuditCronjobBalance::create([
                        'lot_id' => $lot->id,
                        'month' => $month,
                        'year' => $year
                    ]);

                    $errors[] = $lot;
                    // TODO : enviar al administrador del sistema los errores de los estados de cuenta que no se pudieron generar
                    // TODO : - para generarlos manualmente, consultar el log de audit_cronjob_balance para extraer los errores
                    // TODO : - y enviar por correo electrónico
                    die($e->getMessage());
                }

                $purchase_date->addMonth();
            }

            #die('Cargado correctamente.');
        }
        die('Cargado correctamente.');

    });

});
*/






