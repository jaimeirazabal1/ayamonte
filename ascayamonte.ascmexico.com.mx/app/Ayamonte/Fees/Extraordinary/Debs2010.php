<?php namespace Ayamonte\Fees\Extraordinary;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 10:47 PM
 */

use \FeesDebs2010;

/**
 * Class Debs2010
 * @package Ayamonte\Fees\Extraordinary
 */
class Debs2010 {

    /**
     * @var FeesDebs2010
     */
    private $fees;

    /**
     * @param FeesDebs2010 $fees
     */
    public function __construct(FeesDebs2010 $fees)
    {
        $this->fees = $fees;
    }

    /**
     * Obtiene las tarifas por año
     *
     * @param $year_id
     */
    public function getFeesByDate($year_id)
    {
        return $this->fees->byYear($year_id)->get();
    }

    /**
     * Crea las tarfias iniciales de Adeudo 2010
     *
     * @param array $data
     */
    public function create(array $data)
    {
        $this->fees->create($data);
    }

}