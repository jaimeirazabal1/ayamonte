<?php namespace App\Extension\Validation;
/**
 * Created by Netcommerce.mx.
 * User: diegogonzalez
 * Date: 3/20/15
 * Time: 3:45 PM
 */



class CustomValidator extends \Illuminate\Validation\Validator {


    /**
     *
     * Valida que el valor proporcionado sea un formato de Dinero válido
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateMoney($attribute, $value, $parameters)
    {
        $regex1 = '/^[0-9]{1,3}(,[0-9]{3})*(\.[0-9]{1,2})?$/';
        $regex2 = '/^[0-9]+(\.[0-9]{1,2})?$/';

        if(preg_match($regex1, $value) or preg_match($regex2, $value))
        {
            return true;
        }

        return false;
    }


    /**
     *
     * Valida que el valor proporcionado sea un RFC válido
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateRfc($attribute, $value, $parameters)
    {
        if(preg_match('/^([A-Z]{3,4})([0-9]{6})(-|\s)?([A-Z0-9]{3})$/', $value))
        {
            return true;
        }

        return false;
    }

}