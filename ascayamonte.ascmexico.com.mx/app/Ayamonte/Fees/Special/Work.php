<?php namespace Ayamonte\Fees\Special;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 10:47 PM
 */

use \FeesExtraordinarySpecialWork;

/**
 * Class Work
 * @package Ayamonte\Fees\Special
 */
class Work {

    /**
     * @var FeesExtraordinarySpecialWork
     */
    private $fees;

    /**
     * @param FeesExtraordinarySpecialWork $fees
     */
    public function __construct(FeesExtraordinarySpecialWork $fees)
    {
        $this->fees = $fees;
    }


    /**
     * obtiene las tarifas de cuota de diciembre del presente año, enero febreo, marzo, octubre y noviembre del siguiente año
     *
     * @param $year_id
     * @param $next_year_id
     */
    public function getFeesByDate($year_id, $next_year_id)
    {
        #select * from `fees_extraordinary_special_work` where (`year_id` = 7 and month_id = 12) or  (year_id = 8 and month_id < 12);
        $fees = $this->fees->where(function($query) use ($year_id)
        {
            $query->where('year_id', $year_id)->where('month_id', 12);
        })->orWhere(function($query) use ($next_year_id)
        {
            $query->where('year_id', $next_year_id)->where('month_id', '<', 12);
        })->get();

        return $fees;
    }

    /**
     * Crea las tarifas especial de obra
     *
     * @param array $data
     */
    public function create(array $data)
    {
        $this->fees->create($data);
    }

}