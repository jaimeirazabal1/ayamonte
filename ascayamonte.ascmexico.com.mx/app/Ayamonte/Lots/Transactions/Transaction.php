<?php namespace Ayamonte\Lots\Transactions;
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/25/15
 * Time: 12:08 AM
 */

use \Lot;
use \Transaction as TransactionModel;
use \DB;

/**
 * Class Maintenance
 * @property Transaction transaction
 * @package Ayamonte\Lots\Transactions
 */
class Transaction {

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @param TransactionModel $transaction
     */
    public function __construct(TransactionModel $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Actualiza las deudas del condominio : intereses por morosidad, meses de morosidad
     *
     * @param $lot_id
     */
    public function update($lot_id)
    {
        # Actualiza los adeudos marcados como actuales a pendientes
        DB::table('transactions')->where('lot_id', $lot_id)
            ->where('status', 'current')
            ->update(array('status' => 'pending'));

        # obtiene los adeudos pendientes o parcialmente pagados
        $pending_transactions = $this->transaction
                                    ->byLot($lot_id)->pendingOrPartiallyPaid()->oldest()->get();

        foreach($pending_transactions as $transaction)
        {
            if ($transaction->status == 'pending')
            {
                // TODO : calcular los meses en deuda de acuerdo al año y mes de la transaccion, no confiar
                $transaction->indebted_months = ($transaction->indebted_months + 1); # Actualiza los meses en mora
                $transaction->indebted_amount = ((($transaction->amount * $transaction->monthly_interest) / 100)); # Actualiza el monto de intereses
                $transaction->balance_partial = ($transaction->amount  + ($transaction->indebted_amount * $transaction->indebted_months)); # Actualiza el saldo (monto original + intereses)
                $transaction->balance         = ($transaction->amount  + ($transaction->indebted_amount * $transaction->indebted_months)); # Actualiza el saldo (monto original + intereses)
                $transaction->save();
            }
            else if ($transaction->status == 'partially_paid')
            {
                // TODO : calcular los meses en deuda de acuerdo al año y mes de la transaccion, no confiar
                $transaction->indebted_months = ($transaction->indebted_months + 1); # Actualiza los meses en mora
                $transaction_interest         = ((($transaction->balance_partial * $transaction->monthly_interest) / 100)); # Actualiza el monto de intereses
                $transaction->balance_partial = ($transaction->balance_partial  + $transaction_interest); # Actualiza el saldo (monto parcial + intereses)
                $transaction->save();
            }

        }
    }

}