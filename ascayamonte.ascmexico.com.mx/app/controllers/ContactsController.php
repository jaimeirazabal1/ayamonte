<?php

class ContactsController extends \BaseController {

	/**
	 * Display a listing of contacts
	 *
	 * @return Response
	 */
	public function index()
	{
		$contacts = Contact::all();

		return View::make('contacts.index', compact('contacts'));
	}

	/**
	 * Show the form for creating a new contact
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('contacts.create');
	}

	/**
	 * Store a newly created contact in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Contact::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Contact::create($data);

		return Redirect::route('contacts.index');
	}

	/**
	 * Display the specified contact.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$contact = Contact::findOrFail($id);

		return View::make('contacts.show', compact('contact'));
	}

	/**
	 * Show the form for editing the specified contact.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$contact = Contact::find($id);

		return View::make('contacts.edit', compact('contact'));
	}

	/**
	 * Update the specified contact in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$contact = Contact::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Contact::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$contact->update($data);

		return Redirect::route('contacts.index');
	}

	/**
	 * Remove the specified contact from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Contact::destroy($id);

		return Redirect::route('contacts.index');
	}

}
