<?php

/**
 * Class Contact
 */
class Contact extends \Eloquent {

	// Add your validation rules here
    /**
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'email' => 'required',
        'type' => 'required',
        'phone' => 'required',
        'lot_id' => 'required'
	];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'phone',
        'lot_id'
    ];


    /**
     * @return mixed
     */
    public function lot()
    {
        return $this->belongsTo('Lot');
    }
}