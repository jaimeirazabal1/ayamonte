<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 3/25/16
 * Time: 2:40 PM
 */

class AdminPaymentsController extends \AdminController {

    /**
     * @return mixed
     */
    public function index()
    {
        // Codeman Company
        $payments = Payment::with('lot') -> orderBy( 'id', 'DESC') -> paginate( 25 );
//            -> paginate( 25 );

        return View::make('admin.modules.payments.index', compact('payments'));
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $lot_id = Input::get('lot', null);

        $lots = Lot::select('id', DB::raw('CONCAT(official_number, " - ",owner ) AS lot'))
            ->orderBy('official_number')
            ->lists('lot', 'id');

        $lots[''] = 'Elige un condominio..';
        ksort($lots);

        $date = \Carbon\Carbon::now();
        $month = $date->month;
        $year  = $date->year;

            $date_previous_year = \Carbon\Carbon::now()->subYear();
        $prev_year = $date_previous_year->year;
        $prev_month = 12; // Diciembre

        $apply_payment_yearly        = false;
        $meses_anio_actual_por_pagar = 12;
        $discount                    = 0;
        $rate_yearly                 = 0;
        $yearly_amount               = 0;
        $balance                     = 0;

        #Si ya se eligio un condominio, comprobrar el estado de deudas y
        # ver si puede pagar anualidad del presente año, etc, de lo contrario cualquier pago que
        # realice, será abonado a la cuenta
        if ( ! empty($lot_id))
        {
            $lot = Lot::find($lot_id);
            $prev_year_model      = \Year::byYear($prev_year)->first();
            # Obtiene el estatus del año pasado para ver si va al corriente de sus pagos
            $lot_fees_maintenance_prev_year = LotFeeOrdinaryMaintenance::byYear($prev_year_model->id)->byMonth($prev_month)->byLot($lot_id)->first();

            # Si el pago del año pasado aun esta en pendiente, no se puede aplicar el pago anual
            if ( ! empty($lot_fees_maintenance_prev_year) and $lot_fees_maintenance_prev_year->status == 'paid' and ($month == 1 or $month == 2 or $month == 3 or $month == 4))
            {
                $year_model           = \Year::byYear($year)->first();
                $apply_payment_yearly = true;

                $enero      = FeeOrdinaryYearly::byYear($year_model->id)->byMonth(1)->first();
                $febrero    = FeeOrdinaryYearly::byYear($year_model->id)->byMonth(2)->first();
                $marzo      = FeeOrdinaryYearly::byYear($year_model->id)->byMonth(3)->first();
                $abril      = FeeOrdinaryYearly::byYear($year_model->id)->byMonth(4)->first();

                $enero_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(1)->byLot($lot->id)->first();
                $febrero_cuota    = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(2)->byLot($lot->id)->first();
                $marzo_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(3)->byLot($lot->id)->first();
                $abril_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(4)->byLot($lot->id)->first();


                if ( ! empty($enero_cuota) and $enero_cuota->status == 'paid')
                {
                    $meses_anio_actual_por_pagar--;
                }
                if ( ! empty($febrero_cuota) and $febrero_cuota->status == 'paid')
                {
                    $meses_anio_actual_por_pagar--;
                }
                if ( ! empty($marzo_cuota) and $marzo_cuota->status == 'paid')
                {
                    $meses_anio_actual_por_pagar--;
                }
                if ( ! empty($abril_cuota) and $abril_cuota->status == 'paid')
                {
                    $meses_anio_actual_por_pagar--;
                }

                # Descuento aplicado de acuerdo al mes
                if ($month == 1)
                {
                    $discount = $enero->discount_yearly;
                    $rate_yearly = $enero->rate;
                }
                if ($month == 2)
                {
                    $discount = $febrero->discount_yearly;
                    $rate_yearly = $febrero->rate;
                }
                if ($month == 3)
                {
                    $discount = $marzo->discount_yearly;
                    $rate_yearly = $marzo->rate;
                }
                if ($month == 4)
                {
                    $discount = $abril->discount_yearly;
                    $rate_yearly = $abril->rate;
                }

                $yearly_amount = ($meses_anio_actual_por_pagar * $rate_yearly * $lot->m2);
                $yearly_amount_with_discount_cash = (int)($yearly_amount - (($yearly_amount * $discount) / 100));
                $yearly_amount_with_discount_card = (int)($yearly_amount - (($yearly_amount * ($discount - 5)) / 100));
            }

            # Adeudo total
            $transactions = Transaction::with('year')->byLot($lot_id)->notPaid()->oldest()->get();
            $debt_only_current_month = true;


            $amount_ordinary = 0;
            $amount_interest = 0;
            $amount_extra    = 0;
            $balance         = 0;

            foreach($transactions as $transaction)
            {
                switch($transaction->transaction)
                {
                    case 'fee_ordinary_maintenance':
                        $amount_ordinary += $transaction->amount;
                        break;
                    case 'fee_extraordinary_reserve':
                        $amount_extra += $transaction->amount;
                        break;
                    case 'fee_special_work':
                        $amount_extra += $transaction->amount;
                        break;
                    case 'fee_debts_2010':
                        $amount_extra += $transaction->amount;
                        break;
                }

                # Verifica si solo se debe el mes en curso
                if ($transaction->status != 'current')
                {
                    $debt_only_current_month = false;
                }


                $amount_interest += ($transaction->indebted_months * $transaction->indebted_amount);

                $balance += ($transaction->balance_partial);
            }
        }
        // return View::make('admin.modules.payments.create', compact('year', 'lots', 'lot_id', 'apply_payment_yearly', 'meses_anio_actual_por_pagar', 'discount', 'rate_yearly', 'yearly_amount', 'yearly_amount_with_discount_cash', 'yearly_amount_with_discount_card', 'balance', 'debt_only_current_month'));
        // Codeman
        return View::make('admin.modules.payments.create', compact('year', 'lots', 'lot_id', 'apply_payment_yearly', 'meses_anio_actual_por_pagar', 'discount', 'rate_yearly', 'yearly_amount', 'yearly_amount_with_discount_cash', 'yearly_amount_with_discount_card', 'balance', 'debt_only_current_month', 'lot', 'transactions'));
    }

    /**
     *
     */
    public function store()
    {
        $data = Input::all();

//        print_r($data);
//        exit;

        $validator = Validator::make($data, [
            'lot_id' => 'required',
            'amount' => 'required|money',
            'type' => 'required',
            'concept' => 'required'
        ]);

        $data['apply_yearly'] = empty($data['apply_yearly']) ? false : true;
        //  Change by: Codeman Company
//        TODO: Check
//        $data['type']         = empty($data['payment_with_card']) ? 'cash' : 'card';
        $data['meses']        = empty($data['meses']) ? 0 : $data['meses'];
        $data['payment_type_concept'] = empty($data['payment_type_concept']) ? 'lots' : 'other';
        $lot                  = Lot::find($data['lot_id']);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $date = \Carbon\Carbon::now();
        $month = $date->month;
        $year  = $date->year;

        $year_model            = Year::byYear($year)->first(); // Obtiene el año actual
        $apply_discount_total  = 0;
        $apply_discount        = false;
        $rate                  = 0;
        $payment_amount        = 0;

        $payment_amount = convertMoneyToInteger($data['amount']);

        /*dd([
            'lot_id' => $lot->id,
            'amount' => $payment_amount,
            'type' => $data['type'],
            'payment_reference_type' => $data['payment_type_concept'],
            'comments' => $data['comments'],
            'concept' => $data['concept'],
            'payment_type_concept' => $data['payment_type_concept']
        ]);*/

        # Pago de otros conceptos
        if ($data['payment_type_concept'] == 'other')
        {
            try {
                # Registra el pago
                Payment::create([
                    'lot_id' => $lot->id,
                    'amount' => $payment_amount,
                    'type' => $data['type'],
                    'payment_reference_type' => $data['payment_type_concept'],
                    'comments' => $data['comments'],
                    'concept' => $data['concept'],
                    'payment_type_concept' => $data['payment_type_concept']
                ]);

                Flash::success('El pago se ha realizado correctamente.');

                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollback();

                Flash::error('Ocurrió un error al generar el pago.' . $e->getMessage());
                return Redirect::back();
            }

        }
        # El usuario quiere realizar el pago anual
        else if ($data['apply_yearly'] and $data['payment_type_concept'] == 'lots')
        {
            DB::beginTransaction();

            try
            {
                # obtiene las tarifas anuales de acuerdo al año y mes

                $apply_discount        = true;
                $apply_discount_total  = $data['discount'];

                # Valida que el monto a pagar sea el saldo pendiente de los meses restantes
                $amount_expected = ($data['type'] == 'cash') ? $data['amount_yearly_with_discount_cash'] :  $data['amount_yearly_with_discount_card'];

                # Los montos no coinciden
                if ($payment_amount != $amount_expected)
                {
                    Flash::error('Al realizar el pago anual, el monto ingresado debe de coincidir con el monto calculado de acuerdo a la forma de pago seleccionada.');
                    return Redirect::back();
                }


                // Que pasa si las tarifas para los meses despues de abril no estan generadas?
                $enero_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(1)->byLot($lot->id)->first();
                $febrero_cuota    = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(2)->byLot($lot->id)->first();
                $marzo_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(3)->byLot($lot->id)->first();
                $abril_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(4)->byLot($lot->id)->first();
                $mayo_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(5)->byLot($lot->id)->first();
                $junio_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(6)->byLot($lot->id)->first();
                $julio_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(7)->byLot($lot->id)->first();
                $agosto_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(8)->byLot($lot->id)->first();
                $septiembre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(9)->byLot($lot->id)->first();
                $octubre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(10)->byLot($lot->id)->first();
                $noviembre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(11)->byLot($lot->id)->first();
                $diciembre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(12)->byLot($lot->id)->first();

                if ( empty($enero_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 1,
                    ]);

                    $enero_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(1)->byLot($lot->id)->first();
                    $enero_cuota->status = 'paid';
                    $enero_cuota->type   = 'yearly';
                    $enero_cuota->discount_yearly = $apply_discount_total;
                    $enero_cuota->rate_yearly = $data['rate_yearly'];
                    $enero_cuota->save();

                    $enero_trans     = Transaction::byYear($year_model->id)->byMonth(1)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $enero_trans->status = 'paid';
                    $enero_trans->balance_partial = 0;
                    $enero_trans->save();
                }
                if( empty($febrero_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 2
                    ]);

                    $febrero_cuota    = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(2)->byLot($lot->id)->first();
                    $febrero_cuota->status = 'paid';
                    $febrero_cuota->type   = 'yearly';
                    $febrero_cuota->discount_yearly = $apply_discount_total;
                    $febrero_cuota->rate_yearly = $data['rate_yearly'];
                    $febrero_cuota->save();

                    $febrero_trans     = Transaction::byYear($year_model->id)->byMonth(2)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $febrero_trans->status = 'paid';
                    $febrero_trans->balance_partial = 0;
                    $febrero_trans->save();
                }

                if( empty($marzo_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 3
                    ]);

                    $marzo_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(3)->byLot($lot->id)->first();
                    $marzo_cuota->status = 'paid';
                    $marzo_cuota->type   = 'yearly';
                    $marzo_cuota->discount_yearly = $apply_discount_total;
                    $marzo_cuota->rate_yearly = $data['rate_yearly'];
                    $marzo_cuota->save();

                    $marzo_trans     = Transaction::byYear($year_model->id)->byMonth(3)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $marzo_trans->status = 'paid';
                    $marzo_trans->balance_partial = 0;
                    $marzo_trans->save();
                }
                if( empty($abril_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 4
                    ]);

                    $abril_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(4)->byLot($lot->id)->first();
                    $abril_cuota->status = 'paid';
                    $abril_cuota->type   = 'yearly';
                    $abril_cuota->discount_yearly = $apply_discount_total;
                    $abril_cuota->rate_yearly = $data['rate_yearly'];
                    $abril_cuota->save();

                    $abril_trans     = Transaction::byYear($year_model->id)->byMonth(4)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $abril_trans->status = 'paid';
                    $abril_trans->balance_partial = 0;
                    $abril_trans->save();
                }
                if( empty($mayo_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 5
                    ]);

                    $mayo_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(5)->byLot($lot->id)->first();
                    $mayo_cuota->status = 'paid';
                    $mayo_cuota->type   = 'yearly';
                    $mayo_cuota->discount_yearly = $apply_discount_total;
                    $mayo_cuota->rate_yearly = $data['rate_yearly'];
                    $mayo_cuota->save();

                    $mayo_trans     = Transaction::byYear($year_model->id)->byMonth(5)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $mayo_trans->status = 'paid';
                    $mayo_trans->balance_partial = 0;
                    $mayo_trans->save();
                }
                if( empty($junio_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 6
                    ]);

                    $junio_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(6)->byLot($lot->id)->first();
                    $junio_cuota->status = 'paid';
                    $junio_cuota->type   = 'yearly';
                    $junio_cuota->discount_yearly = $apply_discount_total;
                    $junio_cuota->rate_yearly = $data['rate_yearly'];
                    $junio_cuota->save();

                    $junio_trans     = Transaction::byYear($year_model->id)->byMonth(6)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $junio_trans->status = 'paid';
                    $junio_trans->balance_partial = 0;
                    $junio_trans->save();
                }
                if( empty($julio_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 7
                    ]);

                    $julio_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(7)->byLot($lot->id)->first();
                    $julio_cuota->status = 'paid';
                    $julio_cuota->type   = 'yearly';
                    $julio_cuota->discount_yearly = $apply_discount_total;
                    $julio_cuota->rate_yearly = $data['rate_yearly'];
                    $julio_cuota->save();

                    $julio_trans     = Transaction::byYear($year_model->id)->byMonth(7)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $julio_trans->status = 'paid';
                    $julio_trans->balance_partial = 0;
                    $julio_trans->save();
                }
                if( empty($agosto_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 8
                    ]);

                    $agosto_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(8)->byLot($lot->id)->first();
                    $agosto_cuota->status = 'paid';
                    $agosto_cuota->type   = 'yearly';
                    $agosto_cuota->discount_yearly = $apply_discount_total;
                    $agosto_cuota->rate_yearly = $data['rate_yearly'];
                    $agosto_cuota->save();

                    $agosto_trans     = Transaction::byYear($year_model->id)->byMonth(8)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $agosto_trans->status = 'paid';
                    $agosto_trans->balance_partial = 0;
                    $agosto_trans->save();
                }
                if( empty($septiembre_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 9
                    ]);

                    $septiembre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(9)->byLot($lot->id)->first();
                    $septiembre_cuota->status = 'paid';
                    $septiembre_cuota->type   = 'yearly';
                    $septiembre_cuota->discount_yearly = $apply_discount_total;
                    $septiembre_cuota->rate_yearly = $data['rate_yearly'];
                    $septiembre_cuota->save();

                    $septiembre_trans     = Transaction::byYear($year_model->id)->byMonth(9)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $septiembre_trans->status = 'paid';
                    $septiembre_trans->balance_partial = 0;
                    $septiembre_trans->save();
                }
                if( empty($octubre_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 10
                    ]);

                    $octubre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(10)->byLot($lot->id)->first();
                    $octubre_cuota->status = 'paid';
                    $octubre_cuota->type   = 'yearly';
                    $octubre_cuota->discount_yearly = $apply_discount_total;
                    $octubre_cuota->rate_yearly = $data['rate_yearly'];
                    $octubre_cuota->save();

                    $octubre_trans     = Transaction::byYear($year_model->id)->byMonth(10)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $octubre_trans->status = 'paid';
                    $octubre_trans->balance_partial = 0;
                    $octubre_trans->save();
                }
                if( empty($noviembre_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 11
                    ]);

                    $noviembre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(11)->byLot($lot->id)->first();
                    $noviembre_cuota->status = 'paid';
                    $noviembre_cuota->type   = 'yearly';
                    $noviembre_cuota->discount_yearly = $apply_discount_total;
                    $noviembre_cuota->rate_yearly = $data['rate_yearly'];
                    $noviembre_cuota->save();

                    $noviembre_trans     = Transaction::byYear($year_model->id)->byMonth(11)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $noviembre_trans->status = 'paid';
                    $noviembre_trans->balance_partial = 0;
                    $noviembre_trans->save();
                }
                if( empty($diciembre_cuota))
                {
                    Event::fire('lot.monthly.maintenance', [
                        'lot_id' => $lot->id,
                        'year_id' => $year_model->year,
                        'month_id' => 12
                    ]);

                    $diciembre_cuota      = LotFeeOrdinaryMaintenance::byYear($year_model->id)->byMonth(12)->byLot($lot->id)->first();
                    $diciembre_cuota->status = 'paid';
                    $diciembre_cuota->type   = 'yearly';
                    $diciembre_cuota->discount_yearly = $apply_discount_total;
                    $diciembre_cuota->rate_yearly = $data['rate_yearly'];
                    $diciembre_cuota->save();

                    $diciembre_trans     = Transaction::byYear($year_model->id)->byMonth(12)->byLot($lot->id)->byOrdinaryMaintenance()->byCurrent()->first();
                    $diciembre_trans->status = 'paid';
                    $diciembre_trans->balance_partial = 0;
                    $diciembre_trans->save();
                }

//                echo $data[ 'type' ];
//                exit;

                # Registra el pago
                Payment::create([
                    'lot_id' => $lot->id,
                    'amount' => $payment_amount,
                    'type' => $data['type']
                ]);

                Flash::success('El pago anual se ha realizado correctamente.');


                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollback();

                Flash::error('Ocurrió un error al generar el pago anual.' . $e->getMessage());
                return Redirect::back();
            }

        }
        # Solo se abonó dinero a la cuenta
        else
        {
            $amount_paid      = 0;# Monto total pagado
            $amount_remaining = $payment_amount; # Monto a descontar

            $debts_to_pay = Transaction::byLot($lot->id)->notPaid()->oldest()->get();

            DB::beginTransaction();

            try
            {
                foreach($debts_to_pay as $debt)
                {
                    if($amount_remaining <= 0)
                    {
                        break;
                    }

                    switch($debt->status)
                    {
                        case 'pending': case 'current':

                        # Si el pago es mayor o igual al de la deuda actual entonces lo marca como pagado
                        if ($amount_remaining >= $debt->balance)
                        {
                            $debt->status          = 'paid';
                            $amount_paid          += $debt->balance;
                            $amount_remaining      = ($amount_remaining - $debt->balance);
                            $debt->balance_partial = 0;

                            $lotFeeOrdinaryMaintenance = LotFeeOrdinaryMaintenance::byLot($debt->lot_id)->byYear($debt->year_id)->byMonth($debt->month_id)->byPending()->first();
                            $lotFeeOrdinaryMaintenance->status = 'paid';
                            $lotFeeOrdinaryMaintenance->save();
                        }
                        # El pago no cubre la deuda, se abona la cantidad
                        else if($amount_remaining < $debt->balance)
                        {
                            $debt->status          = 'partially_paid';
                            $amount_paid          += $amount_remaining;
                            $debt->balance_partial = ($debt->balance - $amount_remaining);
                            $amount_remaining      = 0;
                        }

                        break;
                        case 'partially_paid':

                            # Si el pago es mayor o igual al de la deuda actual entonces lo marca como pagado
                            if ($amount_remaining >= $debt->balance_partial)
                            {
                                $debt->status          = 'paid';
                                $amount_paid          += $debt->balance_partial;
                                $amount_remaining      = ($amount_remaining - $debt->balance_partial);
                                $debt->balance_partial = 0;

                                $lotFeeOrdinaryMaintenance = LotFeeOrdinaryMaintenance::byLot($debt->lot_id)->byYear($debt->year_id)->byMonth($debt->month_id)->byPending()->first();
                                $lotFeeOrdinaryMaintenance->status = 'paid';
                                $lotFeeOrdinaryMaintenance->save();
                            }
                            # El pago no cubre la deuda, se abona la cantidad
                            else if($amount_remaining < $debt->balance_partial)
                            {
                                $debt->status          = 'partially_paid';
                                $debt->balance_partial = ($debt->balance_partial - $amount_remaining);
                                $amount_paid          += $amount_remaining;
                                $amount_remaining      = 0;
                            }

                            break;
                    }
                    $debt->save();
                }

                if (count($debts_to_pay))
                {

                    # Registra el pago
//  Before
//                    Payment::create([
//                        'lot_id' => $lot->id,
//                        'amount' => $payment_amount,
//                        'type' => $data['type']
//                    ]);

                    //    Change by: Codeman Company
                    Payment::create([
                        'lot_id' => $lot->id,
                        'amount' => $payment_amount,
                        'type' => $data['type'],
                        'concept' => $data['concept'],
                        'comments' => $data['comments']
                    ]);

                    Flash::success('El pago se ha realizado correctamente.');
                }
                else
                {
                    Flash::success('No hay deudas por pagar.');
                }

                if ($amount_remaining > 0)
                {
                    $lot->balance_positive = ($lot->balance_positive + $amount_remaining);
                    $lot->save();
                    Flash::success("El pago se realizó correctamente, la cuenta tiene un saldo a favor de: $" . convertIntegerToMoney($amount_remaining) . " pesos.");
                }

                DB::commit();
            }
            catch(\Exception $e)
            {
                DB::rollback();

                Flash::error('Ocurrió un error al generar el pago.' . $e->getMessage());
                return Redirect::back();
            }
            finally
            {
                #DB::rollback();
            }
        }

        return Redirect::route('admin.payments.index');

    }

    /**
     * @param $id
     * @return string
     */
    public function download($id)
    {
        header("Content-Type: application/pdf");
        $payment = Payment::with('lot')->find($id);
        $lot = Lot::find( $payment -> lot_id );
        $address = DB::table('address')->where('lot_id', $payment -> lot_id )->first();
//        Address::with('lot')->find(263);
        $pdf = PDF::loadView('pdf.payment-recipe', compact('lot','payment','address'));
//        return View::make('pdf.payment-recipe', compact('lot','payment','address'));
//        return $pdf->download('recibo-de-pago.pdf');
        return $pdf->stream('recibo-de-pago.pdf');
    }

//    public function download($payment_id)
//    {
//        $payment = Payment::with('lot')->find($payment_id);
//
//        $pdf = PDF::loadView('pdf.payment-recipe', compact('payment'));
//
//        return 'Pendiente Formato PDF a definir';
//
//        return $pdf->download('recibo-de-pago-' . $payment->id . '.pdf');
//    }

    /**
     * @param $payment_id
     */
    public function show($payment_id)
    {

    }

}