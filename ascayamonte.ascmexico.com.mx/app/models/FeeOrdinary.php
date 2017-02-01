<?php

/**
 * Class FeeOrdinary
 */
class FeeOrdinary extends \Eloquent {

    /**
     * @var string
     */
    protected $table = 'fees_ordinary';

    /**
     * @var array
     */
    public static $rules = [
		'rate_first_days' => 'required|money',
        'rate' => 'required|money',
        'year_id' => 'required|integer',
        'month_id' => 'required|integer'
	];


    /**
     * @var array
     */
    protected $fillable = [
        'rate_first_days',
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