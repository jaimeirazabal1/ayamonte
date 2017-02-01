<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 7/22/15
 * Time: 11:22 PM
 */

class AdminLotsContactsController extends \AdminController {

    /**
     * @param $lot_id
     * @return mixed
     */
    public function create($lot_id)
    {
        $lot = Lot::find($lot_id);

        return View::make('admin.modules.lots.contacts.create', compact('lot'));
    }


    /**
     * Store a newly created lot in storage.
     *
     * @return Response
     */
    public function store($lot_id)
    {
        $data = Input::all();


        $lot = Lot::findOrFail($lot_id);

        Session::flash('tab', 3);

        $data['lot_id'] = $lot_id;

        $validator = Validator::make($data, Contact::$rules);

        if ($validator->fails())
        {
            Flash::error(trans('messages.flash.error'));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $contact = Contact::create($data);

        Flash::success(trans('messages.flash.created'));
        return Redirect::route('admin.lots.edit', $lot->id);
    }


    /**
     * Remove the specified lot from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($lot_id, $id)
    {
        Contact::destroy($id);

        Session::flash('tab', 3);

        Flash::success(trans('messages.flash.deleted'));
        return Redirect::route('admin.lots.edit', $lot_id);
    }

}