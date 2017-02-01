<?php namespace Ayamonte\Notifiers;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 11:43 PM
 */


use \Mail;

class Maintenance {

    /**
     * @param $errors
     * @param $year
     * @param $month
     * @internal param $lot_id
     */
    public function sendErrors($errors, $year, $month)
    {
        $data = [
            'lots' => $errors,
            'year' => $year,
            'month' => $month
        ];

        Mail::send('emails.error-cuotas-mantenimiento', $data, function($message)
        {
            $message->to('diego.h.glez@gmail.com', 'Diego González')->subject('Error al generar cuotas de mantenimiento mensual - Ayamonte.mx');
        });
    }


    /**
     * @param $lot
     * @param $year
     * @param $month
     * @internal param $lot_id
     */
    public function sendByEmailToOwner($lot, $year, $month)
    {
        $data = [
            'lot' => $lot,
            'year' => $year,
            'month' => $month
        ];

        # Si existe algún contacto para enviarle el estado de cuenta, continua y lo envia
        if ( ! empty($lot->contacts[0]))
        {
            $contact = $lot->contacts[0];

            $email   = $contact->email;
            $name    = $contact->name;

            $data['email'] = $email;
            $data['name']  = $name;

            Mail::send('emails.estado-cuenta', $data, function($message) use ($email, $name, $year, $month)
            {
                $message->to($email, $name)->subject('Estado de cuenta - Ayamonte.mx');
            });
        }
    }
}