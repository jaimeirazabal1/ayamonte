<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MonthlyMaintenanceCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'maintenance:monthly';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Genera la cuota mensual de mantenimiento para todos los condominos con la tarifa aplicada del 1 al 15.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $errors = [];

        // Obtiene todos los condominios con sus contactos del tipo propietario
        $lots = Lot::with(['contacts' => function($query)
        {
            $query->where('type', 'owner');
        }])->get();

        $current_date_start = \Carbon\Carbon::now();
        $current_date_end   = \Carbon\Carbon::now();

        #$current_date_start = \Carbon\Carbon::createFromFormat('Y-m', '2016-01');
        #$current_date_end   = \Carbon\Carbon::createFromFormat('Y-m', '2016-01');

        $apply_first_dates_rate = true;

        while($current_date_start->timestamp <= $current_date_end->timestamp)
        {
            $year  = $current_date_start->year;
            $month = $current_date_start->month;

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
                        'month_id' => $month,
                        $apply_first_dates_rate
                    ]);


                    # Envia el estado de cuenta
                    Event::fire('lot.monthly.maintenance.send', [
                        'lot' => $lot->id,
                        'year' => $year,
                        'month' => $month,
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
                }
            }

            $current_date_start->addMonth();
        }


        // Notifica los errores que ocurrieron al generar las cuotas mensuales
        if( count($errors))
        {
            Event::fire('lot.monthly.maintenance.errors', [
                'errors' => $errors,
                'year' => $year,
                'month' => $month
            ]);
        }

    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('early', null, InputOption::VALUE_OPTIONAL, 'Aplicar tarifa con descuento (del 1ro al 15).', false),
		);
	}

}
