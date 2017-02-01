<?php namespace Ayamonte\Lots\Fees\Extraordinary;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/24/15
 * Time: 12:47 AM
 */

use Interest;
use \Mail;
use \Carbon\Carbon;
use Transaction;
use \Year;
use \Exception;
use \Ayamonte\Fees\Extraordinary\Debs2010 as Debs2010Repository;
use \LotFeeDebts2010;
use \Lot;


/**
 * Class Debs2010
 * @package Ayamonte\Lots\Fees\Extraordinary
 */
class Debs2010 {

    /**
     * @var Year
     */
    private $year;

    /**
     * @var Debs2010Repository
     */
    private $debs2010;

    /**
     * @var LotFeeDebts2010
     */
    private $model;
    /**
     * @var Interest
     */
    private $interest;

    /**
     * @param Year $year
     * @param Debs2010Repository $debs2010
     * @param LotFeeDebts2010 $model
     * @param Interest $interest
     */
    public function __construct(Year $year, Debs2010Repository $debs2010, LotFeeDebts2010 $model, Interest $interest)
    {
        $this->year     = $year;
        $this->debs2010 = $debs2010;
        $this->model    = $model;
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

        // TODO : preguntar lo siguiente, Si comienzo a pagarse en enero, el lote se compra en enero, se paga hasta enero del siguiente año o comienza a partir de este
        $current_month = $date->month;

        //
        if ( $current_month == 1) // El mes actual es enero
        {
            $year = $this->year->byYear($date->year)->first();
        }
        else // El mes es febrero, marzo.. se cobra hasta el siguiente año
        {
            $year = $this->year->byYear(($date->year + 1))->first();
        }

        if (empty($year))
        {
            throw new Exception('El año no existe.');
        }

        $fees = $this->debs2010->getFeesByDate($year->id);

        $fees_count = count($fees);

        if ( empty($fees_count))
        {
            throw new Exception('Las tarifas para el adeudo 2010 no están registradas en el año : ' . $year->year);
        }

        foreach($fees as $fee)
        {
            $monthly_payment = (($lot->m2 * $fee->rate) / 12);

            $this->model->create([
                'lot_id' => $lot_id,
                'rate' => $fee->rate,
                'amount' => $monthly_payment,
                'num_payment' => $fee->month_id,
                'month_id' => $fee->month_id,
                'year_id' => $fee->year_id,
                'status' => 'pending'
            ]);
        }
    }

    /**
     * Genera la transaccion de adeudo 2010 para el estado de cuenta en el mes y año proporcionados
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
            throw new \Exception('Error al crear la cuota de adeudo 2010 en la transacción, el año no existe.');
        }

        $fee  = $this->model->byLot($lot_id)->byMonth($month_id)->byYear($year->id)->first();

        # Si encuentra la tarifa en el mes y año proporcionados, genera la transacción
        if ( ! empty($fee))
        {
            $interest = $this->interest->byMonth($month_id)->byYear($year->id)->first();

            if ( empty($interest))
            {
                throw new \Exception('Error al obtener las tarifas de Intereses al generar la cuota de adeudo 2010.');
            }

            # Genera la transaccion de la cuota de mantenimiento mensual
            Transaction::create([
                'lot_id' => $lot_id,
                'month_id' => $fee->month_id,
                'year_id' => $fee->year_id,
                'transaction' => 'fee_debts_2010',
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