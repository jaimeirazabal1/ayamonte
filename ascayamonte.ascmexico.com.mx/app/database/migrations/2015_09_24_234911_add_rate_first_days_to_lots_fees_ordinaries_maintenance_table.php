<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRateFirstDaysToLotsFeesOrdinariesMaintenanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lots_fees_ordinaries_maintenance', function(Blueprint $table)
		{
            $table->integer('rate_first_days')->unsigned()->default(0);
            $table->enum('type', ['monthly', 'yearly'])->default('monthly');
            $table->float('discount_yearly')->unsigned()->default(0);
            $table->integer('rate_yearly')->unsigned()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lots_fees_ordinaries_maintenance', function(Blueprint $table)
		{
			$table->dropColumn('rate_first_days');
            $table->dropColumn('type');
            $table->dropColumn('discount_yearly');
            $table->dropColumn('rate_yearly');
		});
	}

}
