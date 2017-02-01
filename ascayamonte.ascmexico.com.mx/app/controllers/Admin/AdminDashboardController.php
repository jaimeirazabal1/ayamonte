<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 7/22/15
 * Time: 2:57 PM
 */

/**
 * Class DashboardController
 * @package Admin
 */
class AdminDasboardController extends \AdminController {

    /**
     *
     */
    public function index()
    {
        return View::make('admin.modules.dashboard.index');
    }

}