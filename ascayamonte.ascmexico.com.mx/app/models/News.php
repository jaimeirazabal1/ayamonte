<?php

/**
 * Class News
 */
class News extends \Eloquent {

    /**
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'slug' => 'unique:news',
        'description' => 'required',
        'views' => '',
        'status' => ''
	];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'views',
        'status'
    ];

}