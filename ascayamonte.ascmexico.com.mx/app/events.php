<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 10:20 PM
 */

/*
|--------------------------------------------------------------------------
| CONDOMINIO CREADO
|--------------------------------------------------------------------------
*/

Event::listen('lot.created', '\Ayamonte\Lots\Fees\Extraordinary\Reserve@create');
Event::listen('lot.created.reserve', '\Ayamonte\Lots\Fees\Extraordinary\Reserve@create');
Event::listen('lot.created', '\Ayamonte\Lots\Fees\Special\Work@create');
Event::listen('lot.created.specialwork', '\Ayamonte\Lots\Fees\Special\Work@create');
Event::listen('lot.created', '\Ayamonte\Lots\Fees\Extraordinary\Debs2010@create');
Event::listen('lot.created.debs2010', '\Ayamonte\Lots\Fees\Extraordinary\Debs2010@create');


/*
|--------------------------------------------------------------------------
| CONDOMINIO - CARGOS DE MANTENIMIENTO MENSUAL
|--------------------------------------------------------------------------
*/

Event::listen('lot.monthly.maintenance', '\Ayamonte\Lots\Fees\Ordinary\Maintenance@generateByMonth');
Event::listen('lot.monthly.maintenance.send', '\Ayamonte\Notifiers\Maintenance@sendByEmailToOwner');

Event::listen('lot.generate.exrtaordinary.reserve', '\Ayamonte\Lots\Fees\Extraordinary\Reserve@generateTransaction');
Event::listen('lot.generate.exrtaordinary.debts2010', '\Ayamonte\Lots\Fees\Extraordinary\Debs2010@generateTransaction');
Event::listen('lot.generate.special.work', '\Ayamonte\Lots\Fees\Special\Work@generateTransaction');

Event::listen('lot.adjust.maintenance-rates', '\Ayamonte\Lots\Fees\Ordinary\Maintenance@updateMonthlyMaintenanceToStandardRates');

Event::listen('lot.monthly.maintenance.errors', '\Ayamonte\Notifiers\Maintenance@sendErrors');

/*
|--------------------------------------------------------------------------
| CONDOMINIO -ACTUALIZA LAS DEUDAS ANTERIORES
|--------------------------------------------------------------------------
*/

Event::listen('lot.update.debts', '\Ayamonte\Lots\Transactions\Transaction@update');

