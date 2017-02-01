<?php


/**
 * Class User
 */
class User extends Cartalyst\Sentry\Users\Eloquent\User {

    /**
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'activated'
    ];
}