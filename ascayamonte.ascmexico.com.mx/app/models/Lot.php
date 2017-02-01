<?php

/**
 * Class Lot
 */
class Lot extends \Eloquent {

    /**
     * @return array
     */
    public function getDates()
    {
        return [
            'purchase_date',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * @var array
     */
    public static $rules = [
        'reference' => 'required',
        'official_number' => 'required',
        'm2' => 'required|money',
        'cadastral_key' => '',
        'owner' => 'required',
        'lot' => 'required',
        'account_number' => 'required',
        'square_id' => 'required',
        'purchase_date' => 'required'
	];

    /**
     * @param $query
     * @param $term
     * @return mixed
     */
    public function scopeSearch($query, $term)
    {
        //    Change by: Codeman Company
        if( is_numeric( $term ) )
            return $query -> where( 'official_number', $term );
        else
            return $query -> where( 'owner', 'like', '%' . $term . '%' );

//        $term = ('%' . $term .  '%');
//
//        return $query->where('reference', 'like', $term)
//                ->orWhere('owner', 'like', $term)
//                ->orWhere('lot', 'like', $term)
//                ->orWhere('account_number', 'like', $term);
    }


    /**
     * @var array
     */
    protected $fillable = [
        'reference',
        'official_number',
        'm2',
        'cadastral_key',
        'owner',
        'lot',
        'account_number',
        'square_id',
        'purchase_date'
    ];

    /**
     * @return mixed
     */
    public function square()
    {
        return $this->belongsTo('Square');
    }


    /**
     * @return mixed
     */
    public function address()
    {
        return $this->hasOne('Address');
    }


    /**
     * @return mixed
     */
    public function contacts()
    {
        return $this->hasMany('Contact');
    }

    /**
     * @return mixed
     */
    public function payments()
    {
        return $this->hasMany('Payment');
    }

}