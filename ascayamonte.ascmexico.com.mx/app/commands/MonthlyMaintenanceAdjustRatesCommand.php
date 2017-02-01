<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class MonthlyMaintenanceAdjustRatesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'maintenance:adjust';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Ajusta la cuota mensual de mantenimiento después del día 16 de cada mes.';


    /**
     * Create a new command instance.
     *
     * @param Maintenance $maintenance
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
                    # Ajusta la cuota de mantenimiento mensual a la tarifa estandar
                    Event::fire('lot.adjust.maintenance-rates', [
                        'lot_id' => $lot->id,
                        'year_id' => $year,
                        'month_id' => $month,
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
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
