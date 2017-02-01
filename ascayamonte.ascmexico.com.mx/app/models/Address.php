<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 7/23/15
 * Time: 10:42 PM
 */

class Address extends \Eloquent {


    /**
     * @var string
     */
    protected $table = 'address';

    /**
     * @var array
     */
    public static $rules = [
        'address' => 'required',
        'neighborhood' => 'required',
        'zipcode' => 'required',
        'city' => 'required',
        'state' => 'required',
        'lot_id' => 'required'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'address',
        'neighborhood',
        'zipcode',
        'city',
        'state',
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