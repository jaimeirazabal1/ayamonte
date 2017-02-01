<?php

class AdminNewsController extends \AdminController {

	/**
	 * Display a listing of news
	 *
	 * @return Response
	 */
	public function index()
	{
		$news = News::paginate(25);

		return View::make('admin.modules.news.index', compact('news'));
	}

	/**
	 * Show the form for creating a new news
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.modules.news.create');
	}

	/**
	 * Store a newly created news in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), News::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

        $data['status'] = (bool) $data['status'];

		News::create($data);

		return Redirect::route('admin.news.index');
	}

	/**
	 * Show the form for editing the specified news.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$news = News::find($id);

		return View::make('admin.modules.news.edit', compact('news'));
	}

	/**
	 * Update the specified news in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$news = News::findOrFail($id);

		$validator = Validator::make($data = Input::all(), [
            'title' => 'required',
            'slug' => 'unique:news,slug,' . $news->id,
            'description' => 'required',
            'views' => '',
            'status' => ''
        ]);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

        $data['status'] = (bool) $data['status'];

		$news->update($data);

		return Redirect::route('admin.news.index');
	}

}
