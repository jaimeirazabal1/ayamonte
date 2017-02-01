<?php

/**
 * Class FeesDebs2010
 */
class FeesDebs2010 extends \Eloquent {

    /**
     * @var string
     */
    protected $table = 'fees_debs_2010';

    /**
     * @var array
     */
    public static $rules = [
        'rate' => 'required|money',
        'year_id' => 'required|integer',
        'month_id' => 'required|integer'
	];

    /**
     * @var array
     */
    protected $fillable = [
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