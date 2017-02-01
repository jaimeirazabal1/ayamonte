<?php

/**
 * Class Square
 */
class Square extends \Eloquent {

    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Ordena las plazas en orden alfabetico ascendente
     *
     * @return mixed
     */
    public function scopeOrderAlpha($query)
    {
        return $query->orderBy('name', 'asc');
    }


    /**
     * @return mixed
     */
    public function lots()
    {
        return $this->hasMany('Lot');
    }

    /**
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}