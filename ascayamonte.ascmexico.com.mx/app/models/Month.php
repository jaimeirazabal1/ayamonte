<?php

/**
 * Class Month
 */
class Month extends \Eloquent {

    /**
     * @var array
     */
    public static $rules = [

	];


    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    /**
     * @return mixed
     */
    public function feesordinaries()
    {
        return $this->hasMany('FeeOrdinary');
    }

    /**
     * @return mixed
     */
    public function feesordinariesyearly()
    {
        return $this->hasMany('FeeOrdinaryYearly');
    }

    /**
     * @return mixed
     */
    public function feeddebs2010()
    {
        return $this->hasMany('FeesDebs2010');
    }

    /**
     * @return mixed
     */
    public function feesextraordinariesspecialworks()
    {
        return $this->hasMany('FeesExtraordinarySpecialWork');
    }

    /**
     * @return mixed
     */
    public function feesextraordinaryreserve()
    {
        return $this->hasMany('FeesExtraordinayReserve');
    }

    /**
     * @return mixed
     */
    public function interests()
    {
        return $this->hasMany('Interest');
    }

}