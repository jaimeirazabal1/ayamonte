<?php

class AdminYearsController extends \AdminController {

    /**
     * Display a listing of years
     *
     * @return Response
     */
    public function index()
    {
        $years  = Year::with('interests', 'feesordinaries', 'feesordinariesyearly', 'feesextraordinariesreserves', 'feesextraordinaryspecialwork', 'feesdebs2010')->paginate(25);
        $months = Month::lists('name', 'id');

        return View::make('admin.modules.years.index', compact('years', 'months'));
    }

    /**
     * Show the form for creating a new fee
     *
     * @return Response
     */
    public function create()
    {
        $months = Month::lists('name', 'id');

        return View::make('admin.modules.years.create', compact('months'));
    }

    /**
     * Store a newly created fee in storage.
     *
     * @return Response
     */
    public function store()
    {
        $data   = Input::all();
        $months = Month::all();

        # Valida el año
        $validator = Validator::make($data, Year::$rules);

        if ($validator->fails())
        {
            Flash::error(trans('messages.flash.error'));
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $message = '';

        DB::beginTransaction();

        try
        {
            $year = Year::create([
                'year' => $data['year']
            ]);

            # Cuota ordinaria de mantenimiento mensual
            foreach($data['fees_ordinary'] as $monthID => $info)
            {
                $info = [
                    'rate_first_days' => convertMoneyToInteger($info['rate_first_days']),
                    'rate' => convertMoneyToInteger($info['rate']),
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, FeeOrdinary::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota de mantenimiento ordinaria mensual sea correcta.';
                    throw new Exception('Error en cuota ordinaria mensual');
                }

                FeeOrdinary::create($info);
            }

            # Cuota ordinaria de mantenimiento anual
            foreach($data['fees_ordinary_yearly'] as $monthID => $info)
            {
                $info = [
                    'rate' => convertMoneyToInteger($info['rate']),
                    'discount_yearly' => $info['discount_yearly'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, FeeOrdinaryYearly::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota de mantenimiento ordinaria anual sea correcta.';
                    throw new Exception('Error en cuota ordinaria anual');
                }

                FeeOrdinaryYearly::create($info);
            }
            ############################################################################

            # Valida la tarifa de cuota especial de obra
            $fees_extraordinary_special_work_rate = $data['fees_extraordinary_special_work_rate'];

            $validator = Validator::make([
                    'fees_extraordinary_special_work_rate' => $fees_extraordinary_special_work_rate
                ],
                [
                'fees_extraordinary_special_work_rate' => 'required|money'
            ]);

            if ($validator->fails())
            {
                $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                throw new Exception('Error en Cuota Especial de Obra');
            }

            ###################### valida la tarifa especial de cuota de obra, dinero
            $fees_extraordinary_special_work_money = $data['fees_extraordinary_special_work_money'];

            foreach($data['fees_extraordinary_special_work_money'] as $monthID => $info)
            {
                $info = [
                    'mount' => $info['mount'],
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, [
                    'mount' => 'required|money'
                ]);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                    throw new Exception('Error en Cuota Especial de Reserva');
                }
            }

            $fees_extraordinary_special_work_percent = $data['fees_extraordinary_special_work_percent'];

            foreach($data['fees_extraordinary_special_work_percent'] as $monthID => $info)
            {
                $info = [
                    'percent' => $info['percent'],
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, [
                    'percent' => 'required|money'
                ]);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                    throw new Exception('Error en Cuota Especial de Reserva');
                }
            }

            $next_year = Year::where('year', ($year->year + 1))->first();

            if ( empty($next_year))
            {
                $next_year = Year::create([
                    'year' => ($year->year + 1)
                ]);
            }

            $fees_extraordinary_special_work_rate = convertMoneyToInteger($fees_extraordinary_special_work_rate);

            # Diciembre del presenta año
            FeesExtraordinarySpecialWork::create([
                   'rate' => $fees_extraordinary_special_work_rate,
                   'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['12']['mount']),
                   'percent' => 0,
                   'year_id' => $year->id,
                   'month_id' => 12,
                   'num_payment' => 1,
                   'complementary' => false
            ]);
            # Enero del siguiente año
            FeesExtraordinarySpecialWork::create([
                'rate' => $fees_extraordinary_special_work_rate,
                'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['1']['mount']),
                'percent' => 0,
                'year_id' => $next_year->id,
                'month_id' => 1,
                'num_payment' => 2,
                'complementary' => false
            ]);
            # Febrero del siguiente año
            FeesExtraordinarySpecialWork::create([
                'rate' => $fees_extraordinary_special_work_rate,
                'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['2']['mount']),
                'percent' => 0,
                'year_id' => $next_year->id,
                'month_id' => 2,
                'num_payment' => 3,
                'complementary' => false
            ]);
            # Marzo del siguiente año
            FeesExtraordinarySpecialWork::create([
                'rate' => $fees_extraordinary_special_work_rate,
                'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['3']['mount']),
                'percent' => 0,
                'year_id' => $next_year->id,
                'month_id' => 3,
                'num_payment' => 4,
                'complementary' => false
            ]);
            # Octubre del siguiente año
            FeesExtraordinarySpecialWork::create([
                'rate' => $fees_extraordinary_special_work_rate,
                'mount' => 0,
                'percent' => $fees_extraordinary_special_work_percent['10']['percent'],
                'year_id' => $next_year->id,
                'month_id' => 10,
                'num_payment' => 5,
                'complementary' => true
            ]);
            # Noviembre del siguiente año
            FeesExtraordinarySpecialWork::create([
                'rate' => $fees_extraordinary_special_work_rate,
                'mount' => 0,
                'percent' => $fees_extraordinary_special_work_percent['11']['percent'],
                'year_id' => $next_year->id,
                'month_id' => 11,
                'num_payment' => 6,
                'complementary' => true
            ]);

            ####################### RESERVA

            foreach($data['fees_extraordinary_reserve'] as $monthID => $info)
            {
                $info = [
                    'rate' => convertMoneyToInteger($info['rate']),
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, FeesExtraordinayReserve::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota Especial de Reserva sea correcta.';
                    throw new Exception('Error en Cuota Especial de Reserva');
                }

                FeesExtraordinayReserve::create($info);
            }

            ####################### DEUDA 2010

            $rate_debs_2010 = 0;


            # Valida la tarifa de cuota especial de obra
            $fees_debs_2010 = $data['fees_debs_2010'];

            $validator = Validator::make([
                'fees_debs_2010' => $fees_debs_2010
            ],
                [
                    'fees_debs_2010' => 'required|money'
                ]);

            if ($validator->fails())
            {
                $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                throw new Exception('Error en Cuota Adeudo 2010');
            }

            foreach($months as $month)
            {
                FeesDebs2010::create([
                    'rate' => convertMoneyToInteger($fees_debs_2010),
                    'year_id' => $year->id,
                    'month_id' => $month->id
                ]);
            }

            ####################### INTERESES
            foreach($data['interests'] as $monthID => $info)
            {
                $info = [
                    'rate' => $info['rate'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, Interest::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Intereses por mes sea correcta.';
                    throw new Exception('Error en Intereses por mes');
                }

                Interest::create($info);
            }

        }
        catch(Exception $e)
        {
            DB::rollback();
            Flash::error($message);
            return Redirect::back()->withErrors($validator)->withInput();
        }

        DB::commit();

        Flash::success(trans('messages.flash.created'));
        return Redirect::route('admin.years.index');
    }


    /**
     * Show the form for editing the specified fee.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $year   = Year::with('interests', 'feesordinaries', 'feesordinariesyearly', 'feesextraordinariesreserves', 'feesextraordinaryspecialwork', 'feesdebs2010')->find($id);
        $months = Month::lists('name', 'id');

        $interests = [];
        $feesordinaries = [];
        $feesordinariesyearly = [];
        $feesextraordinariesreserves = [];
        $feesextraordinaryspecialwork = [];
        $feesdebs2010 = [];

        foreach($year->interests as $interest)
        {
            $interests[$interest->month_id] = convertIntegerToMoney($interest->rate);
        }


        foreach($year->feesordinaries as $feesordinary)
        {
            $feesordinaries[$feesordinary->month_id]['rate_first_days'] = convertIntegerToMoney($feesordinary->rate_first_days);
            $feesordinaries[$feesordinary->month_id]['rate']            = convertIntegerToMoney($feesordinary->rate);
        }

        foreach($year->feesordinariesyearly as $feesordinary)
        {
            $feesordinariesyearly[$feesordinary->month_id]['discount_yearly'] = $feesordinary->discount_yearly;
            $feesordinariesyearly[$feesordinary->month_id]['rate']            = convertIntegerToMoney($feesordinary->rate);
        }

        foreach($year->feesextraordinariesreserves as $feesextraordinaryreserve)
        {
            $feesextraordinariesreserves[$feesextraordinaryreserve->month_id] = convertIntegerToMoney($feesextraordinaryreserve->rate);
        }


        $feesextraordinaryspecialworkrate = null;

        /*foreach($year->feesextraordinaryspecialwork as $feesextraordinaryspecialworko)
        {
            $feesextraordinaryspecialworkrate = convertIntegerToMoney($feesextraordinaryspecialworko->rate);
            $feesextraordinaryspecialwork['12']['mount'] = convertIntegerToMoney($feesextraordinaryspecialworko->mount);
        }*/

        $feesextraordinaryspecialworko = FeesExtraordinarySpecialWork::where('year_id', $year->id)->where('month_id', 12)->first();

        $feesextraordinaryspecialworkrate = empty($feesextraordinaryspecialworko) ? null : convertIntegerToMoney($feesextraordinaryspecialworko->rate);
        $feesextraordinaryspecialwork['12']['mount'] = empty($feesextraordinaryspecialworko) ? null : convertIntegerToMoney($feesextraordinaryspecialworko->mount);

        $next_year = Year::where('year', ($year->year + 1))->first();

        if ( ! empty($next_year))
        {
            $feeworkEnero  = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 1)->first();
            $feeworkFebrero = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 2)->first();
            $feeworkMarzo = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 3)->first();
            $feeworkOctubre = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 10)->first();
            $feeworkNoviembre = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 11)->first();

            $feesextraordinaryspecialwork['1']['mount'] = convertIntegerToMoney($feeworkEnero->mount);
            $feesextraordinaryspecialwork['2']['mount'] = convertIntegerToMoney($feeworkFebrero->mount);
            $feesextraordinaryspecialwork['3']['mount'] = convertIntegerToMoney($feeworkMarzo->mount);
            $feesextraordinaryspecialwork['10']['percent'] = $feeworkOctubre->percent;
            $feesextraordinaryspecialwork['11']['percent'] = $feeworkNoviembre->percent;
        }

        $feesdebs2010 = null;
        foreach($year->feesdebs2010 as $feesdeb2010)
        {
            $feesdebs2010[$feesdeb2010->month_id] = convertIntegerToMoney($feesdeb2010->rate);
            $feesdebs2010 = convertIntegerToMoney($feesdeb2010->rate);

        }


        return View::make('admin.modules.years.edit', compact('year', 'months', 'feesextraordinaryspecialworkrate', 'feesdebs2010',  'interests', 'feesordinaries', 'feesordinariesyearly', 'feesextraordinariesreserves', 'feesextraordinaryspecialwork', 'feesdebs2010'));
    }

    /**
     * Update the specified fee in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $year = Year::findOrFail($id);
        $months = Month::all();
        $data = Input::all();

        $message = '';

        DB::beginTransaction();

        try
        {
            foreach($data['fees_ordinary'] as $monthID => $info)
            {
                $info = [
                    'rate_first_days' => $info['rate_first_days'],
                    'rate' => $info['rate'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, FeeOrdinary::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota ordinaria sea correcta.';
                    throw new Exception('Error en cuota ordinaria');
                }

                $info = [
                    'rate_first_days' => convertMoneyToInteger($info['rate_first_days']),
                    'rate' => convertMoneyToInteger($info['rate']),
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                $feeordinary = FeeOrdinary::where('year_id', $year->id)->where('month_id', $monthID)->first();

                if (empty($feeordinary))
                {
                    FeeOrdinary::create($info);
                }
                else
                {
                    $feeordinary->update($info);
                }
            }

            ######################################
            # Cuota ordinaria de mantenimiento anual
            foreach($data['fees_ordinary_yearly'] as $monthID => $info)
            {
                $info = [
                    'rate' => $info['rate'],
                    'discount_yearly' => $info['discount_yearly'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, FeeOrdinaryYearly::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota de mantenimiento ordinaria anual sea correcta.';
                    throw new Exception('Error en cuota ordinaria anual');
                }

                $info = [
                    'rate' => convertMoneyToInteger($info['rate']),
                    'discount_yearly' => $info['discount_yearly'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                $feeordinary = FeeOrdinaryYearly::where('year_id', $year->id)->where('month_id', $monthID)->first();

                if ( empty($feeordinary))
                {
                    FeeOrdinaryYearly::create($info);
                }
                else
                {
                    $feeordinary->update($info);
                }
            }


            ############################################################################
            # Valida la tarifa de cuota especial de obra
            $fees_extraordinary_special_work_rate = $data['fees_extraordinary_special_work_rate'];

            $validator = Validator::make([
                'fees_extraordinary_special_work_rate' => $fees_extraordinary_special_work_rate
            ],
                [
                    'fees_extraordinary_special_work_rate' => 'required|money'
                ]);

            if ($validator->fails())
            {
                $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                throw new Exception('Error en Cuota Especial de Obra');
            }

            ###################### valida la tarifa especial de cuota de obra, dinero
            $fees_extraordinary_special_work_money = $data['fees_extraordinary_special_work_money'];

            foreach($data['fees_extraordinary_special_work_money'] as $monthID => $info)
            {
                $info = [
                    'mount' => $info['mount'],
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, [
                    'mount' => 'required|money'
                ]);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                    throw new Exception('Error en Cuota Especial de Reserva');
                }
            }

            $fees_extraordinary_special_work_percent = $data['fees_extraordinary_special_work_percent'];

            foreach($data['fees_extraordinary_special_work_percent'] as $monthID => $info)
            {
                $info = [
                    'percent' => $info['percent'],
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, [
                    'percent' => 'required|money'
                ]);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                    throw new Exception('Error en Cuota Especial de Reserva');
                }
            }

            $next_year = Year::where('year', ($year->year + 1))->first();

            if ( empty($next_year))
            {
                $next_year = Year::create([
                    'year' => ($year->year + 1)
                ]);
            }


            $fees_extraordinary_special_work_rate = convertMoneyToInteger($fees_extraordinary_special_work_rate);

            # Diciembre del presenta año
            $feeWorkDic = FeesExtraordinarySpecialWork::where('year_id', $year->id)->where('month_id', 12)->first();
            $feeWorkEne = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 1)->first();
            $feeWorkFeb = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 2)->first();
            $feeWorkMar = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 3)->first();
            $feeWorkOct = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 10)->first();
            $feeWorkNov = FeesExtraordinarySpecialWork::where('year_id', $next_year->id)->where('month_id', 11)->first();


            if ( empty($feeWorkDic))
            {
                # Diciembre del presenta año
                FeesExtraordinarySpecialWork::create([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['12']['mount']),
                    'percent' => 0,
                    'year_id' => $year->id,
                    'month_id' => 12,
                    'num_payment' => 1,
                    'complementary' => false
                ]);
            }
            else
            {
                $feeWorkDic->update([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['12']['mount']),
                ]);
            }

            if ( empty($feeWorkEne))
            {
                # Enero del siguiente año
                FeesExtraordinarySpecialWork::create([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['1']['mount']),
                    'percent' => 0,
                    'year_id' => $next_year->id,
                    'month_id' => 1,
                    'num_payment' => 2,
                    'complementary' => false
                ]);
            }
            else
            {
                # Enero del siguiente año
                $feeWorkEne->update([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['1']['mount']),
                ]);
            }

            if ( empty($feeWorkFeb))
            {
                # Febrero del siguiente año
                FeesExtraordinarySpecialWork::create([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['2']['mount']),
                    'percent' => 0,
                    'year_id' => $next_year->id,
                    'month_id' => 2,
                    'num_payment' => 3,
                    'complementary' => false
                ]);
            }
            else
            {
                # Febrero del siguiente año
                $feeWorkFeb->update([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['2']['mount']),
                ]);
            }

            if ( empty($feeWorkMar))
            {
                # Marzo del siguiente año
                FeesExtraordinarySpecialWork::create([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['3']['mount']),
                    'percent' => 0,
                    'year_id' => $next_year->id,
                    'month_id' => 3,
                    'num_payment' => 4,
                    'complementary' => false
                ]);
            }
            else
            {
                # Marzo del siguiente año
                $feeWorkMar->update([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => convertMoneyToInteger($fees_extraordinary_special_work_money['3']['mount']),
                ]);
            }

            if ( empty($feeWorkOct))
            {
                # Octubre del siguiente año
                FeesExtraordinarySpecialWork::create([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => 0,
                    'percent' => $fees_extraordinary_special_work_percent['10']['percent'],
                    'year_id' => $next_year->id,
                    'month_id' => 10,
                    'num_payment' => 5,
                    'complementary' => true
                ]);
            }
            else
            {
                # Octubre del siguiente año
                $feeWorkOct->update([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'percent' => $fees_extraordinary_special_work_percent['10']['percent'],
                ]);
            }


            if ( empty($feeWorkNov))
            {
                # Noviembre del siguiente año
                FeesExtraordinarySpecialWork::create([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'mount' => 0,
                    'percent' => $fees_extraordinary_special_work_percent['11']['percent'],
                    'year_id' => $next_year->id,
                    'month_id' => 11,
                    'num_payment' => 6,
                    'complementary' => true
                ]);
            }
            else
            {
                # Noviembre del siguiente año
                $feeWorkNov->update([
                    'rate' => $fees_extraordinary_special_work_rate,
                    'percent' => $fees_extraordinary_special_work_percent['11']['percent'],
                ]);
            }


            ######################################
            foreach($data['fees_extraordinary_reserve'] as $monthID => $info)
            {
                $info = [
                    'rate' => $info['rate'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, FeesExtraordinayReserve::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Cuota Especial de Reserva sea correcta.';
                    throw new Exception('Error en Cuota Especial de Reserva');
                }

                $info = [
                    'rate' => convertMoneyToInteger($info['rate']),
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                $feeextraordinaryreserve = FeesExtraordinayReserve::where('year_id', $year->id)->where('month_id', $monthID)->first();

                if ( empty($feeextraordinaryreserve))
                {
                    FeesExtraordinayReserve::create($info);
                }
                else
                {
                    $feeextraordinaryreserve->update($info);
                }
            }


            ######################################
            $rate_debs_2010 = 0;


            # Valida la tarifa de cuota especial de obra
            $fees_debs_2010 = $data['fees_debs_2010'];

            $validator = Validator::make([
                'fees_debs_2010' => $fees_debs_2010
            ],
                [
                    'fees_debs_2010' => 'required|money'
                ]);

            if ($validator->fails())
            {
                $message = 'Por favor verifica que la información de Cuota Especial de Obra sea correcta.';
                throw new Exception('Error en Cuota Adeudo 2010');
            }

            foreach($months as $month)
            {
                $feedebs2010 = FeesDebs2010::where('year_id', $year->id)->where('month_id', $monthID)->first();

                if ( empty($feedebs2010))
                {
                    FeesDebs2010::create([
                        'rate' => convertMoneyToInteger($fees_debs_2010),
                        'year_id' => $year->id,
                        'month_id' => $month->id
                    ]);
                }
                else
                {
                    $feedebs2010->update([
                        'rate' => convertMoneyToInteger($fees_debs_2010)
                    ]);
                }
            }


            #####################################
            foreach($data['interests'] as $monthID => $info)
            {
                $info = [
                    'rate' => $info['rate'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                # Valida la cuota ordinaria
                $validator = Validator::make($info, Interest::$rules);

                if ($validator->fails())
                {
                    $message = 'Por favor verifica que la información de Intereses por mes sea correcta.';
                    throw new Exception('Error en Intereses por mes');
                }

                $info = [
                    'rate' => $info['rate'],
                    'year_id' => $year->id,
                    'month_id' => $monthID
                ];

                $feeinterest = Interest::where('year_id', $year->id)->where('month_id', $monthID)->first();

                if ( empty($feeinterest))
                {
                    Interest::create($info);
                }
                else
                {
                    $feeinterest->update($info);
                }
            }

        }
        catch(Exception $e)
        {
            DB::rollback();
            Flash::error($message);
            return Redirect::back()->withInput();
        }

        DB::commit();

        Flash::success(trans('messages.flash.updated'));
        return Redirect::route('admin.years.index');
    }


}
