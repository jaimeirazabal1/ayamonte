<?php namespace Ayamonte\Lots\Fees\Ordinary;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/23/15
 * Time: 11:43 PM
 */

use \Mail;
use \Carbon\Carbon;
use \Year;
use \Exception;
use \Ayamonte\Fees\Ordinary\Maintenance as MaintenanceRepository;
use \LotFeeOrdinaryMaintenance;
use \Lot;
use \Transaction;
use \Interest;


/**
 * Class Maintenance
 * @package Ayamonte\Lots\Fees\Ordinary
 */
class Maintenance {

    /**
     * @var Year
     */
    private $year;

    /**
     * @var MaintenanceRepository
     */
    private $maintenance;

    /**
     * @var LotFeeOrdinaryMaintenance
     */
    private $model;
    /**
     * @var Interest
     */
    private $interest;

    /**
     * @param Year $year
     * @param MaintenanceRepository $maintenance
     * @param LotFeeOrdinaryMaintenance $model
     * @param Interest $interest
     */
    public function __construct(Year $year, MaintenanceRepository $maintenance, LotFeeOrdinaryMaintenance $model, Interest $interest)
    {
        $this->year         = $year;
        $this->maintenance  = $maintenance;
        $this->model        = $model;
        $this->interest     = $interest;
    }

    /**
     * @param $lot_id
     * @param null $year
     * @param null $month
     * @param $rate_first_days
     * @throws Exception
     */
    public function generateByMonth($lot_id, $year = null, $month = null, $rate_first_days = false)
    {
        // Todo : consultar de repositorio
        $lot = Lot::find($lot_id);

        # Obtiene la fecha actual
        if (empty($year) or empty($month))
        {
            $date = Carbon::now();

            $year  = $date->year;
            $month = $date->month;
        }

        $year = $this->year->byYear($year)->first(); // Obtiene el año actual

        if (empty($year))
        {
            throw new Exception('El año no existe.');
        }


        #El mes de reserva se paga en Diciembre
        $fee = $this->maintenance->getFeesByDate($year->id, $month);

        if ( empty($fee))
        {
            throw new Exception('Las tarifas para la cuota de mantenimiento no estan registradas.');
        }

        # Obtiene los intereses
        $interest = $this->interest->byMonth($month)->byYear($year->id)->first();

        if ( empty($interest))
        {
            throw new Exception('Las tarifas para los intereses no estan registrados.');
        }

        # Monto calculado
        if ( $rate_first_days )
        {
            $amount = ($fee->rate_first_days * $lot->m2);
        }
        else
        {
            $amount = ($fee->rate * $lot->m2);
        }



        # Genera el registro de la cuota de mantenimiento mensual
        $lotFeeMaintenance = $this->model->create([
            'lot_id' => $lot->id,
            'rate' => $fee->rate,
            'amount' => $amount,
            'month_id' => $fee->month_id,
            'year_id' => $fee->year_id,
            'status' => 'pending',
            'rate_first_days' => $fee->rate_first_days,
            'type' => 'monthly',
            'discount_yearly' => 0  ,
            'rate_yearly' => 0
        ]);

        # Genera la transaccion de la cuota de mantenimiento mensual
        $lotFeeMaintenanceTransaction = Transaction::create([
            'lot_id' => $lot->id,
            'month_id' => $fee->month_id,
            'year_id' => $fee->year_id,
            'transaction' => 'fee_ordinary_maintenance',
            'indebted_months' => 0,
            'indebted_amount' => 0,
            'monthly_interest' => $interest->rate,
            'amount' => $amount,
            'balance' => $amount,
            'balance_partial' => $amount,
            'status' => 'current'
        ]);

        # Si hay saldo a favor (Solo existe el saldo a favor cuando ya no hay deudas pendientes y el lote está al día en sus deudas)
        if ( $lot->balance_positive > 0)
        {
            # La cuota de mantenimiento mensual se cubre con el saldo a favor
            if ($lot->balance_positive >= $amount)
            {
                $lot->balance_positive = ($lot->balance_positive - $amount);
                $lot->save();

                # Actualiza la cuota de mantenimiento y su transacción
                $lotFeeMaintenance->status = 'paid';
                $lotFeeMaintenance->save();

                $lotFeeMaintenanceTransaction->status = 'paid';
                $lotFeeMaintenanceTransaction->balance_partial = 0;
                $lotFeeMaintenanceTransaction->save();
            }
            # El saldo a favor no cubre la cuota mensual, se abona a la cuota de mantenimiento
            else
            {
                $balance_positive = $lot->balance_positive;

                $lot->balance_positive = ($lot->balance_positive - $amount);
                $lot->save();

                # Actualiza la cuota de mantenimiento y su transacción
                $lotFeeMaintenance->status = 'pending';
                $lotFeeMaintenance->save();

                $lotFeeMaintenanceTransaction->status = 'partially_paid';
                $lotFeeMaintenanceTransaction->balance_partial = abs($lotFeeMaintenanceTransaction->balance - $balance_positive);
                $lotFeeMaintenanceTransaction->save();
            }
        }
    }

    public function updateMonthlyMaintenanceToStandardRates($lot_id, $year = null, $month = null)
    {
        // Todo : consultar de repositorio
        $lot = Lot::find($lot_id);

        # Obtiene la fecha actual
        if (empty($year) or empty($month))
        {
            $date = Carbon::now();

            $year  = $date->year;
            $month = $date->month;
        }

        $year = $this->year->byYear($year)->first(); // Obtiene el año actual

        if (empty($year))
        {
            throw new Exception('El año no existe.');
        }


        #El mes de reserva se paga en Diciembre
        $fee = $this->maintenance->getFeesByDate($year->id, $month);

        if ( empty($fee))
        {
            throw new Exception('Las tarifas para la cuota de mantenimiento no estan registradas.');
        }

        # Costo de mantenimiento mensual para la tarifa del 1 al 15 del mes
        $amount_first_days = ($fee->rate_first_days * $lot->m2);

        # Monto calculado
        $amount = ($fee->rate * $lot->m2);

        # Actualiza el registro de la cuota de mantenimiento mensual
        $lot_monthly_maintenance = $this->model->byYear($fee->year_id)->byMonth($fee->month_id)->byLot($lot->id)->byPending()->first();
        $lot_monthly_maintenance->amount = $amount;
        $lot_monthly_maintenance->save();


        # Genera la transaccion de la cuota de mantenimiento mensual
        $lot_transaction_monthly_maintenance = Transaction::byLot($lot->id)->byYear($fee->year_id)->byMonth($fee->month_id)->byCurrentOrPartiallyPaid()->first();

        if ( $lot_transaction_monthly_maintenance->status == 'partially_paid' )
        {
            # Si se hizo un pago parcial del saldo a favor, entonces se recalcula a la tarifa estandar y se descuenta el saldo a favor
            $adjust_balance_in_favor_standar_rate = $amount_first_days - $lot_transaction_monthly_maintenance->balance_partial;
            $lot_transaction_monthly_maintenance->balance_partial = ($amount - $adjust_balance_in_favor_standar_rate);
        }
        else if ( $lot_transaction_monthly_maintenance->status == 'current' )
        {
            $lot_transaction_monthly_maintenance->balance_partial = $amount;
        }

        $lot_transaction_monthly_maintenance->balance = $amount;
        $lot_transaction_monthly_maintenance->amount = $amount;
        $lot_transaction_monthly_maintenance->save();
    }

}