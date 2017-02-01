<?php namespace Ayamonte\Lots\Fees\Special;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 11:43 PM
 */

use \Interest;
use \Mail;
use \Carbon\Carbon;
use Transaction;
use \Year;
use \Exception;
use \Ayamonte\Fees\Special\Work as WorkRepository;
use \LotFeeSpecialWork;
use \Lot;

class Work {

    /**
     * @var Year
     */
    private $year;

    private $work;

    private $model;
    /**
     * @var Interest
     */
    private $interest;

    /**
     * @param Year $year
     * @param WorkRepository $work
     * @param LotFeeSpecialWork $model
     * @param Interest $interest
     */
    public function __construct(Year $year, WorkRepository $work, LotFeeSpecialWork $model, Interest $interest)
    {
        $this->year    = $year;
        $this->work    = $work;
        $this->model   = $model;
        $this->interest = $interest;
    }

    /**
     * @param $lot_id
     * @throws Exception
     */
    public function create($lot_id)
    {
        // Todo : consultar de repositorio
        $lot = Lot::find($lot_id);

        # Obtiene la fecha actual
        #$date = Carbon::now();
        $date = Carbon::createFromFormat('Y-m-d', '2011-10-22');

        $this_year      = $date->year;
        $next_year      = ($date->year + 1);

        // TODO : preguntar lo siguiente, suponiendo que estamos en 31/dic/Año, cuando se paga? se supone que ya se genero el estado de cuenta
        $year_current = $this->year->byYear($this_year)->first(); // Obtiene el mes de diciembre del año en curso
        $year_next    = $this->year->byYear($next_year)->first(); // Obtiene enero, febrero, marzo, octubre y noviembre del siguiente año


        if (empty($year_current) or empty($year_next))
        {
            throw new Exception('El año no existe.');
        }


        #El mes de reserva se paga en Diciembre
        $fees = $this->work->getFeesByDate($year_current->id, $year_next->id);

        if ( empty($fees))
        {
            throw new Exception('Las tarifas para la cuota especial de obra no estan registradas.');
        }

        $sum_amount = 0;

        foreach($fees as $fee)
        {
            if ( ! $fee->complementary)
            {
                $sum_amount += $fee->mount;
            }
            else
            {
                $amount_lot_m2 = $fee->rate * $lot->m2;
                $percent_amount = ($fee->percent > 0) ? (((($amount_lot_m2 - $sum_amount) * $fee->percent) / 100)) : 0;
            }

            $this->model->create([
                'lot_id' => $lot_id,
                'rate' => $fee->rate,
                'amount' => ($fee->complementary) ? ($percent_amount) : $fee->mount,
                'complementary' => $fee->complementary,
                'percent' => $fee->percent,
                'num_payment' => $fee->num_payment,
                'month_id' => $fee->month_id,
                'year_id' => $fee->year_id,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Genera la transaccion de cuota especial de obra para el estado de cuenta
     *
     * @param $lot_id
     * @param $year
     * @param $month_id
     * @throws Exception
     * @internal param $year_id
     */
    public function generateTransaction($lot_id, $year, $month_id)
    {
        $year = $this->year->byYear($year)->first();

        if ( empty($year))
        {
            throw new \Exception('Error al crear la cuota especial de obra en la transacción, el año no existe.');
        }

        $fee  = $this->model->byLot($lot_id)->byMonth($month_id)->byYear($year->id)->first();

        # Si encuentra la tarifa en el mes y año proporcionados, genera la transacción
        if ( ! empty($fee))
        {
            $interest = $this->interest->byMonth($month_id)->byYear($year->id)->first();

            if ( empty($interest))
            {
                throw new \Exception('Error al obtener las tarifas de Intereses al generar la cuota especial de obra.');
            }

            # Genera la transaccion de la cuota de mantenimiento mensual
            Transaction::create([
                'lot_id' => $lot_id,
                'month_id' => $fee->month_id,
                'year_id' => $fee->year_id,
                'transaction' => 'fee_special_work',
                'indebted_months' => 0,
                'indebted_amount' => 0,
                'monthly_interest' => $interest->rate,
                'amount' => $fee->amount,
                'balance' => $fee->amount,
                'balance_partial' => 0,
                'status' => 'current'
            ]);
        }
    }

}