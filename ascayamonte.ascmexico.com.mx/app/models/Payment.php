<?php

/**
 * Class Payment
 */
class Payment extends \Eloquent {

    /**
     * @var array
     */
    protected $fillable = [
        'lot_id',
        'amount',
        'type',
        'payment_reference_type',
        'comments',
        'concept',
        'payment_type_concept'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByCash($query)
    {
        return $query->where('type', 'cash');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeByCard($query)
    {
        return $query->where('type', 'card');
    }

    /**
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * @return mixed
     */
    public function transactions()
    {
        return $this->belongsToMany('Transaction');
    }

    /**
     * @return mixed
     */
    public function lot()
    {
        return $this->belongsTo('Lot');
    }
}