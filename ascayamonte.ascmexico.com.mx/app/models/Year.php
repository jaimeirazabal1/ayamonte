<?php

/**
 * Class Year
 */
class Year extends \Eloquent {

    /**
     * @var array
     */
    public static $rules = [
		'year' => 'required|integer|min:2010|max:2050|unique:years'
	];

    /**
     * @var array
     */
    protected $fillable = [
        'year'
    ];

    /**
     * @param $query
     * @param $year
     * @return mixed
     */
    public function scopebyYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * @return mixed
     */
    public function interests()
    {
        return $this->hasMany('Interest');
    }

    /**
     * @return mixed
     */
    public function feesordinaries()
    {
        return $this->HasMany('FeeOrdinary');
    }

    /**
     * @return mixed
     */
    public function feesordinariesyearly()
    {
        return $this->HasMany('FeeOrdinaryYearly');
    }

    /**
     * @return mixed
     */
    public function feesextraordinariesreserves()
    {
        return $this->hasMany('FeesExtraordinayReserve');
    }


    /**
     * @return mixed
     */
    public function feesextraordinaryspecialwork()
    {
        return $this->hasMany('FeesExtraordinarySpecialWork');
    }

    /**
     * @return mixed
     */
    public function feesdebs2010()
    {
        return $this->hasMany('FeesDebs2010');
    }

    /**
     * @return mixed
     */
    public function transactions()
    {
        return $this->hasMany('Transaction');
    }
}