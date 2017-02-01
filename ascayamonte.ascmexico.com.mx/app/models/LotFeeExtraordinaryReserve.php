<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 9:42 PM
 */

class LotFeeExtraordinaryReserve extends \Eloquent {

    /**
     * @var string
     */
    protected $table = 'lots_fees_extraordinary_reserve';

    /**
     * @var array
     */
    protected $fillable = [
        'lot_id',
        'rate',
        'number_payment',
        'amount',
        'month_id',
        'year_id',
        'status'
    ];

    /**
     * @param $query
     * @param $lot_id
     * @return mixed
     */
    public function scopeByLot($query, $lot_id)
    {
        return $query->where('lot_id', $lot_id);
    }

    /**
     * @param $query
     * @param $month_id
     * @return mixed
     */
    public function scopeByMonth($query, $month_id)
    {
        return $query->where('month_id', $month_id);
    }

    /**
     * @param $query
     * @param $year_id
     * @return mixed
     */
    public function scopeByYear($query, $year_id)
    {
        return $query->where('year_id', $year_id);
    }

}