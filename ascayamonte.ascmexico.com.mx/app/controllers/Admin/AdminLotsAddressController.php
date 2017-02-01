<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 7/23/15
 * Time: 10:55 PM
 */

class AdminLotsAddressController extends \AdminController {


    public function createOrUpdate($lot_id, $id = null)
    {
        $data = Input::all();
        $lot = Lot::findOrFail($lot_id);
        $validator = Validator::make($data, Address::$rules);

        Session::flash('tab', 2);


        if ($validator->fails())
        {
            Flash::error(trans('messages.flash.error'));
            return Redirect::back()->withErrors($validator)->withInput();
        }


        $data['lot_id'] = $lot_id;

        if ( empty($id))
        {
            $address = Address::create($data);
            Flash::success(trans('messages.flash.created'));
        }
        else
        {
            $address = Address::findOrFail($id);
            $address->update($data);
            Flash::success(trans('messages.flash.updated'));
        }


        return Redirect::route('admin.lots.edit', array($lot->id, 'tab' => '2'));
    }

}