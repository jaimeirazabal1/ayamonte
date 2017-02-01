<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/25/15
 * Time: 12:12 AM
 */

class Transaction extends \Eloquent {

    /**
     * @var string
     */
    protected $table = 'transactions';

    /**
     * @var array
     */
    protected $fillable = [
        'lot_id',
        'month_id',
        'year_id',
        'transaction',
        'indebted_months',
        'indebted_amount',
        'monthly_interest',
        'amount',
        'balance',
        'balance_partial',
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
     * @param $year_id
     * @return mixed
     */
    public function scopeByYear($query, $year_id)
    {
        return $query->where('year_id', $year_id);
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
     * @return mixed
     */
    public function scopeByOrdinaryMaintenance($query)
    {
        return $query->where('transaction', 'fee_ordinary_maintenance');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByExtraordinaryReserve($query)
    {
        return $query->where('transaction', 'fee_extraordinary_reserve');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeBySpecialWork($query)
    {
        return $query->where('transaction', 'fee_special_work');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByDebts2010($query)
    {
        return $query->where('transaction', 'fee_debts_2010');
    }

    /**
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeByTransaction($query, $type)
    {
        return $query->where('transaction', $type);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByPending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePendingOrPartiallyPaid($query)
    {
        return $query->where(function($q){
            $q->where('status', '=', 'pending')
                ->orWhere('status', '=', 'partially_paid');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotPaid($query)
    {
        return $query->where(function($q){
            $q->where('status', '=', 'pending')
                ->orWhere('status', '=', 'current')
                ->orWhere('status', '=', 'partially_paid');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByPaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByCurrent($query)
    {
        return $query->where('status', 'current');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByCurrentOrPartiallyPaid($query)
    {
        return $query->where(function($q){
            $q->where('status', '=', 'current')
                ->orWhere('status', '=', 'partially_paid');
        });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('year_id', 'asc')->orderBy('month_id', 'asc');
    }

    /**
     * @return mixed
     */
    public function payments()
    {
        return $this->belongsToMany('Payment');
    }


    /**
     * @return mixed
     */
    public function year()
    {
        return $this->belongsTo('Year');
    }


}