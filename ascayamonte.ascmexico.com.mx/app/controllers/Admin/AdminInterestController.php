<?php

class AdminInterestController extends \AdminController {

    /**
     * Display a listing of Interests
     *
     * @return Response
     */
    public function index()
    {
        $interests = Interest::paginate(25);

        return View::make('admin.modules.interest.index', compact('interests'));
    }

    /**
     * Show the form for creating a new Interest
     *
     * @return Response
     */
    public function create()
    {
        $year       = date('Y');
        $year_limit = ($year + 1);
        $years      = Year::where('year', '>=', $year)->where('year', '<=', $year_limit)->lists('year', 'id');
        $months     = Month::lists('name', 'id');

        return View::make('admin.modules.interest.create', compact('years', 'months'));
    }

    /**
     * Store a newly created Interest in storage.
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::all();

        $validator = Validator::make($data, [
            'year_id' => 'required|integer'
        ]);

        // Valida el año
        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }


        $validator = Validator::make($data, Interest::$rules);

        DB::beginTransaction();

        try
        {
            foreach($data['rates'] as $monthId => $rate)
            {
                if ($validator->fails())
                {
                    return Redirect::back()->withErrors($validator)->withInput();
                }

                Interest::create([
                    'rate' => empty($rate['rate']) ? null : $rate['rate'],
                    'year_id' => $data['year_id'],
                    'month_id' => $monthId
                ]);
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Flash::error(trans('messages.flash.error'));
            return Redirect::back()->withInput();
        }

        DB::commit();

        return Redirect::route('admin.interest.index');
    }



    /**
     * Show the form for editing the specified Interest.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $interest   = Interest::find($id);
        $year       = date('Y');
        $year_limit = ($year + 1);
        $years      = Year::where('year', '>=', $year)->where('year', '<=', $year_limit)->lists('year', 'id');
        $months     = Month::lists('name', 'id');

        return View::make('admin.modules.interest.edit', compact('interest', 'years', 'months'));
    }

    /**
     * Update the specified Interest in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $Interest = Interest::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Interest::$rules);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $Interest->update($data);

        return Redirect::route('admin.interest.index');
    }
}
