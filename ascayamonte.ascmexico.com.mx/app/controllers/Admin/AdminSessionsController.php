<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 7/22/15
 * Time: 3:04 PM
 */

class AdminSessionsController extends \AdminController {


    /**
     *
     */
    public function create()
    {
        return View::make('admin.modules.sessions.create');
    }


    /**
     *
     */
    public function store()
    {
        $data = Input::all();

        $rules = array(
            'email'    => 'required|email',
            'password' => 'required'
        );

        // LOGIN ATTEMPTS
        /*$user_attempt = Sentry::findUserByLogin($data['email']);
        $user_temp    = Sentry::findThrottlerByUserId($user_attempt->id);

        $attempts     = $user_temp->getLoginAttempts();
        Session::put('user_attempt_id', $user_attempt->id);

        if ( $attempts >= 3)
        {
            $rules =  array_add($rules, 'captcha_phrase', 'required');
        }

        // Desactiva la cuenta
        if ( $attempts >= 6)
        {
            $user_attempt->activated = false;
            $user_attempt->save();

            Flash::error('El usuario : ' . $data['email'] . ' ha sido desactivado, por favor contacte al administrador del sistema para volver a activar la cuenta.');
            return Redirect::back()->withInput();
        }
        */


        $validator = Validator::make($data, $rules);

        if ($validator->fails())
        {
            Flash::error(trans('messages.flash.error'));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        /*if ( ! empty($data['captcha_phrase']))
        {
            if ( ! (Session::get('captcha.phrase') == $data['captcha_phrase']) )
            {
                $user_temp->addLoginAttempt();

                Flash::error('Los carácteres de la imagen no coinciden con los proporcionados. Inténtelo de nuevo por favor.' . Session::get('captcha.phrase') . ' - ' . $attempts);
                return Redirect::back()->withInput();
            }
        }*/

        $input   = $data;
        $message = '';

        try
        {
            // Login credentials
            $credentials = array(
                'email'    => $input['email'],
                'password' => $input['password'],
            );

            /*// Desactiva la cuenta
            if ( $attempts >= 6)
            {
                $user_attempt->activated = false;
                $user_attempt->save();

                Flash::error('El usuario : ' . $data['email'] . ' ha sido desactivado, por favor contacte al administrador del sistema para volver a activar la cuenta.');
                return Redirect::back()->withInput();
            }
            */

            // Authenticate the user
            $user = Sentry::authenticate($credentials, false);

            /*$user_temp    = Sentry::findThrottlerByUserId($user_attempt->id);
            $user_temp->clearLoginAttempts();


            Session::forget('captcha.phrase');
            */

            /*Event::fire(
                'user.loggedin', array(
                    'user' => $user->id
                )
            );*/

            Session::put('user', $user);

            return Redirect::route('admin.dashboard');
        }
        catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            $message = 'El correo electrónico es obligatorio.';
        }
        catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
        {
            $message = 'La contraseña es obligatoria.';
        }
        catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
        {
            $message = 'El correo electrónico y/o contraseña no coinciden.';
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $message = 'El usuario no existe o ha sido eliminado del sistema.';
        }
        catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            $message = 'El usuario ha sido desactivado, por favor contacte al administrador del sistema para volver a activar la cuenta.';
        }

        catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
        {
            $message = 'El usuario ha sido suspendido por un lapso de 15 minutos.';
        }
        catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
            $message = 'El usuario ha sido banneado del sistema.';
        }


        Flash::error($message);

        /*if ( $attempts >= 3)
        {
            if ( ! (Session::get('captcha.phrase') == $data['captcha_phrase']) )
            {
                Flash::error('Los carácteres de la imagen no coinciden con los proporcionados. Inténtelo de nuevo por favor.' . Session::get('captcha.phrase') . ' - ' . $attempts);
            }
        }*/

        return Redirect::back()->withInput();
    }

    /**
     *
     */
    public function passwordRecovery()
    {
        return View::make('admin.modules.sessions.password-recovery');
    }

    /**
     *
     */
    public function passwordRecoverySend()
    {
        $validator = Validator::make($data = Input::all(), array(
            'email' => 'required|email'
        ));

        if ($validator->fails())
        {
            Flash::error(trans('messages.flash.error'));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try
        {
            // Find the user using the user email address
            $user = Sentry::findUserByLogin($data['email']);

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();

            $user_data = array(
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'fullname'   => $user->first_name . ' ' . $user->last_name,
                'email'      => $user->email,
                'reset_code' => $resetCode,
            );

            Mail::send('emails.auth.reminder', $user_data, function($message) use ($user_data)
            {
                $message->to($user_data['email'], $user_data['fullname'])->subject('Restablecer contraseña - ');
            });

            Flash::success('Se ha enviado un correo electrónico con información para restablecer tu contraseña.');
            return Redirect::back();
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Flash::error('El correo electrónico no se encuentra registrado en el sistema');
            return Redirect::back()->withInput();
        }
        catch(\Exception $e)
        {
            Flash::error('Ocurrió un error al intentar restablecer la contraseña. Intentalo más tarde.');
            return Redirect::back()->withInput();
        }
    }

    /**
     * @param $token
     */
    public function passwordReset($email, $token)
    {
        return View::make('admin.modules.sessions.password-reset', compact('email', 'token'));
    }


    /**
     * @param $token
     */
    public function passwordResetSend($email, $token)
    {
        $data = Input::all();

        $validator = Validator::make($data, array(
            'password'              => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8'
        ));

        if ($validator->fails())
        {
            Flash::error(trans('messages.flash.error'));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        try
        {
            $user = Sentry::findUserByLogin($email);

            if ($user->checkResetPasswordCode($token))
            {
                // Attempt to reset the user password
                if ($user->attemptResetPassword($token, $data['password']))
                {
                    Flash::success('La contraseña se estableció correctamente.');
                    return Redirect::route('admin.sessions.create');
                }
                else
                {
                    Flash::error('Ocurrió un error al restablecer la contraseña, intenta de nuevo por favor.');
                    return Redirect::route('admin.sessions.password-reset');
                }
            }
            else
            {
                Flash::error('El token para restablecer la contraseña no es válido');
                return Redirect::route('admin.sessions.create');
            }
        }
        catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Flash::error('El correo electrónico no se encuentra registrado en el sistema');
            return Redirect::back()->withInput();
        }

    }

    /**
     *
     */
    public function destroy()
    {
        if ( ! Sentry::check())
        {
            return Redirect::route('admin.sessions.create');
        }


        /*Event::fire(
            'user.loggedout', array(
                'user' => Session::get('user')->id,
            )
        );
        */

        Sentry::logout();
        Session::flush();

        return Redirect::route('admin.sessions.create');
    }

}