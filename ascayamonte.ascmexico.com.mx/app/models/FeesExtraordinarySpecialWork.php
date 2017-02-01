<?php

/**
 * Class FeesExtraordinarySpecialWork
 */
class FeesExtraordinarySpecialWork extends \Eloquent {

    /**
     * @var string
     */
    protected $table = 'fees_extraordinary_special_work';

    /**
     * @var array
     */
    public static $rules = [
        'rate' => 'required|money',
        'mount' => '',
        'percent' => '',
        'year_id' => 'required|integer',
        'month_id' => 'required|integer',
        'num_payment' => '',
        'complementary' => '',
        'mount' => '',
	];

    /**
     * @var array
     */
    protected $fillable = [
        'rate',
        'mount',
        'percent',
        'year_id',
        'month_id',
        'num_payment',
        'complementary',
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
     * @param $query
     * @param $month_id
     * @return mixed
     */
    public function scopeBeforeMonth($query, $month_id)
    {
        return $query->where('month_id', '<', $month_id);
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