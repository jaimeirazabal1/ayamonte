<?php namespace App\Providers;
    /**
     * Created by Netcommerce.mx.
     * User: diegogonzalez
     * Date: 3/13/15
     * Time: 11:18 AM
     */


/**
 * Class MoneyServiceProvider
 * @package App\Providers
 */
class CustomValidatorServiceProvider extends \Illuminate\Support\ServiceProvider {


    /**
     *
     */
    public function register()
    {

    }

    /**
     *
     */
    public function boot()
    {
        $this->app->validator->resolver(function($translator, $data, $rules, $messages)
        {
            return new \App\Extension\Validation\CustomValidator($translator, $data, $rules, $messages);
        });
    }



}