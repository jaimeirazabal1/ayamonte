<?php

/**
 * Class AdminLotsController
 */
class AdminLotsController extends \AdminController {

	/**
	 * Display a listing of lots
	 *
	 * @return Response
	 */
	public function index()
	{
        $input = Input::all();
        
        $query = is_null($input) ? null : $input['q'];

        if ( ! empty($query) )
        {
            $lots = Lot::with('square', 'contacts')->search($query)->paginate(25);
        }
        else {
            $lots = Lot::with('square', 'contacts')->search( '' )->paginate(25);
        }

		return View::make('admin.modules.lots.index', compact('lots'));
	}

	/**
	 * Show the form for creating a new lot
	 *
	 * @return Response
	 */
	public function create()
	{
        $squares = $this->squaresOptions();
        $types = $this->typeContactOptions();

        $tab = 1;

		return View::make('admin.modules.lots.create', compact('squares', 'types', 'tab'));
	}

	/**
	 * Store a newly created lot in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Lot::$rules);

		if ($validator->fails())
		{
            Flash::error(trans('messages.flash.error'));
			return Redirect::back()->withErrors($validator)->withInput();
		}

        $data['m2']            = formatToMeters($data['m2']);
        $data['purchase_date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $data['purchase_date'])->toDateTimeString();

        DB::beginTransaction();

        try
        {
            $lot = Lot::create($data);

            # Crea las cuota de reserva
            # Crea la cuota especial de obra
            # Crea las cuotas de adeudo 2010
            Event::fire('lot.created', ['lot_id' => $lot->id]);

        }
        catch(\Exception $e)
        {
            DB::rollback();
            Flash::error(trans('messages.flash.error') . ' - Por favor verifique que las tarifas existan para el año en curso. Error : ' . $e->getMessage());
            return Redirect::back()->withErrors($validator)->withInput();
        }


        DB::commit();
        Flash::success(trans('messages.flash.created'));
		return Redirect::route('admin.lots.edit', $lot->id);
	}


	/**
	 * Show the form for editing the specified lot.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $squares = $this->squaresOptions();
        $types = $this->typeContactOptions();
		$lot = Lot::with('address', 'contacts')->find($id);

        $tab = Session::get('tab', 1);

		return View::make('admin.modules.lots.edit', compact('lot', 'squares', 'types', 'tab'));
	}

	/**
	 * Update the specified lot in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$lot = Lot::findOrFail($id);

		$validator = Validator::make($data = Input::all(), [
            'reference' => 'required',
            'official_number' => 'required',
            'm2' => 'money',
            'cadastral_key' => '',
            'owner' => 'required',
            'lot' => 'required',
            'account_number' => 'required',
            'square_id' => 'required'
        ]);


		if ($validator->fails())
		{
            Flash::error(trans('messages.flash.error'));
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$lot->update($data);

        Flash::success(trans('messages.flash.updated'));
		return Redirect::route('admin.lots.edit', $lot->id);
	}


    /**
     * @return mixed
     */
    private function squaresOptions()
    {
        return Square::orderAlpha()->lists('name', 'id');
    }

    /**
     * @return array
     */
    private function typeContactOptions()
    {
        return array(
            'owner' => 'Propietario',
            'guest' => 'Inquilino',
            'assistant' => 'Asistente'
        );
    }

    /**
     * @param $id
     * @return string
     */
    public function sendByEmail($id)
    {
        $lot  = Lot::with(['contacts' => function($query)
        {
            $query->where('type', 'owner');
        }])->find($id);

        $date = \Carbon\Carbon::now();

        # Envia el estado de cuenta
        Event::fire('lot.monthly.maintenance.send', [
            'lot' => $lot,
            'year' => $date->year,
            'month' => $date->month,
        ]);

        Flash::success('El estado de cuenta se ha enviado correctamente.');
        return Redirect::back();
    }

    /**
     * @param $id
     * @return string
     */
    public function download($id)
    {
        $lot = Lot::with(['contacts' => function($query)
        {
            $query->where('type', 'owner');
        }])->find($id);

        $transactions = Transaction::with('year')->byLot($id)->notPaid()->oldest()->get();

        $pdf = PDF::loadView('pdf.account-status', compact('lot', 'transactions'));

        return $pdf->download('estado-de-cuenta.pdf');
    }


    /**
     * @param $id
     * @return string
     */
    public function show($id)
    {
        $lot = Lot::find($id);
//		echo $lot;
//		exit;

        $transactions = Transaction::with('year')->byLot($id)->notPaid()->oldest()->get();

        return View::make('admin.modules.lots.show', compact('lot', 'transactions'));
    }

    /**
     * @param $id
     * @return string
     */
    public function payments($id)
    {
        $lot = Lot::find($id);

        return 'Pagos del lote';
    }

}
