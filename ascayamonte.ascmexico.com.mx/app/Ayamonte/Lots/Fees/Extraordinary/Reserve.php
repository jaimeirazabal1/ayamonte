<?php namespace Ayamonte\Lots\Fees\Extraordinary;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 10:27 PM
 */

use Interest;
use \Mail;
use \Carbon\Carbon;
use Transaction;
use \Year;
use \Exception;
use \Ayamonte\Fees\Extraordinary\Reserve as ReserveRepository;
use \LotFeeExtraordinaryReserve;
use \Lot;

/**
 * Class Reserve
 * @package Ayamonte\Lots\Fees\Extraordinary
 */
class Reserve {

    /**
     * @var Year
     */
    private $year;

    /**
     * @var ReserveRepository
     */
    private $reserve;

    /**
     * @var LotFeeExtraordinaryReserve
     */
    private $model;
    /**
     * @var Interest
     */
    private $interest;

    /**
     * @param Year $year
     * @param ReserveRepository $reserve
     * @param LotFeeExtraordinaryReserve $model
     */
    public function __construct(Year $year, ReserveRepository $reserve, LotFeeExtraordinaryReserve $model, Interest $interest)
    {
        $this->year     = $year;
        $this->reserve  = $reserve;
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

        # Busca el año en curso
        $year = $this->year->byYear($date->year)->first();

        if (empty($year))
        {
            throw new Exception('El año no existe.');
        }

        #El mes de reserva se paga en Diciembre
        // TODO : preguntar lo siguiente, suponiendo que estamos en 2/dic/Año, cuando se paga? se supone que ya se genero el estado de cuenta
        $december_month = 12;
        $fee = $this->reserve->getFeesByDate($year->id, $december_month);

        if ( empty($fee))
        {
            throw new Exception('Las tarifas para la cuota de reserva no estan registradas para el año : ' . $year->year);
        }

        $this->model->create([
            'lot_id' => $lot_id,
            'rate' => $fee->rate,
            'number_payment' => 1,
            'amount' => ($lot->m2 * $fee->rate),
            'month_id' => $fee->month_id,
            'year_id' => $fee->year_id,
            'status' => 'pending'
        ]);
    }

    /**
     * Genera la transaccion de reserva para el estado de cuenta
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
            throw new \Exception('Error al crear la cuota de reserva en la transacción, el año no existe.');
        }

        $fee  = $this->model->byLot($lot_id)->byMonth($month_id)->byYear($year->id)->first();

        # Si encuentra la tarifa en el mes y año proporcionados, genera la transacción
        if ( ! empty($fee))
        {
            $interest = $this->interest->byMonth($month_id)->byYear($year->id)->first();

            if ( empty($interest))
            {
                throw new \Exception('Error al obtener las tarifas de Intereses al generar la cuota de reserva.');
            }

            # Genera la transaccion de la cuota de mantenimiento mensual
            Transaction::create([
                'lot_id' => $lot_id,
                'month_id' => $fee->month_id,
                'year_id' => $fee->year_id,
                'transaction' => 'fee_extraordinary_reserve',
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