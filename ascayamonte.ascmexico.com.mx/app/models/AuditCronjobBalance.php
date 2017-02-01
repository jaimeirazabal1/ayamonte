<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 9/26/15
 * Time: 1:47 AM
 */

class AuditCronjobBalance extends \Eloquent {

    /**
     * @var string
     */
    protected $table = 'audit_cronjob_balances';

    /**
     * @var array
     */
    protected $fillable = [
        'lot_id',
        'month',
        'year',
        'status'
    ];
}