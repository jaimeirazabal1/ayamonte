<?php namespace Ayamonte\Fees\Extraordinary;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 10:47 PM
 */

use \FeesExtraordinayReserve;

/**
 * Class Reserve
 * @package Ayamonte\Fees\Extraordinary
 */
class Reserve {

    /**
     * @var FeesExtraordinayReserve
     */
    private $fees;

    /**
     * @param FeesExtraordinayReserve $fees
     */
    public function __construct(FeesExtraordinayReserve $fees)
    {
        $this->fees = $fees;
    }

    /**
     * Obtiene las tarifas por año y mes indicados
     *
     * @param $year_id
     * @param $month_id
     */
    public function getFeesByDate($year_id, $month_id)
    {
        return $this->fees->byYear($year_id)->byMonth($month_id)->first();
    }

    /**
     * Crea las tarifas iniciales de Reserva
     *
     * @param array $data
     */
    public function create(array $data)
    {
        $this->fees->create($data);
    }

}