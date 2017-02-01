<?php

/**
 * Class FeeOrdinaryYearly
 */
class FeeOrdinaryYearly extends \Eloquent {

    /**
     * @var string
     */
    protected $table = 'fees_ordinary_yearly';

    /**
     * @var array
     */
    public static $rules = [
        'discount_yearly' => 'required|money',
        'rate' => 'required|money',
        'year_id' => 'required|integer',
        'month_id' => 'required|integer'
    ];


    /**
     * @var array
     */
    protected $fillable = [
        'discount_yearly',
        'rate',
        'year_id',
        'month_id'
    ];

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
     * @return mixed
     */
    public function year()
    {
        return $this->belongsTo('Year');
    }

    /**
     * @return mixed
     */
    public function month()
    {
        return $this->belongsTo('Month');
    }

}