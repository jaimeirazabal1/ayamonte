<?php namespace Ayamonte\Fees\Ordinary;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/24/15
 * Time: 9:32 PM
 */

use \FeeOrdinary;


/**
 * Class Maintenance
 * @package Ayamonte\Fees\Ordinary
 */
class Maintenance {

    /**
     * @var FeeOrdinary
     */
    private $fee;

    /**
     * @param FeeOrdinary $fee
     */
    public function __construct(FeeOrdinary $fee)
    {
        $this->fee = $fee;
    }

    /**
     * Obtiene las tarifas por año y mes indicados
     *
     * @param $year
     * @param $month
     */
    public function getFeesByDate($year, $month)
    {
        return $this->fee->byYear($year)->byMonth($month)->first();
    }

}