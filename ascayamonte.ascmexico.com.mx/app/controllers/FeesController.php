<?php

class FeesController extends \BaseController {

	/**
	 * Display a listing of fees
	 *
	 * @return Response
	 */
	public function index()
	{
		$fees = Fee::all();

		return View::make('fees.index', compact('fees'));
	}

	/**
	 * Show the form for creating a new fee
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('fees.create');
	}

	/**
	 * Store a newly created fee in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Fee::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Fee::create($data);

		return Redirect::route('fees.index');
	}

	/**
	 * Display the specified fee.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$fee = Fee::findOrFail($id);

		return View::make('fees.show', compact('fee'));
	}

	/**
	 * Show the form for editing the specified fee.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$fee = Fee::find($id);

		return View::make('fees.edit', compact('fee'));
	}

	/**
	 * Update the specified fee in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$fee = Fee::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Fee::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$fee->update($data);

		return Redirect::route('fees.index');
	}

	/**
	 * Remove the specified fee from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Fee::destroy($id);

		return Redirect::route('fees.index');
	}

}
