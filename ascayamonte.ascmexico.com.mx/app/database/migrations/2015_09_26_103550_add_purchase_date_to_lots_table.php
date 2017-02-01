<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPurchaseDateToLotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lots', function(Blueprint $table)
		{
			$table->datetime('purchase_date');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lots', function(Blueprint $table)
		{
			$table->dropColumn('purchase_date');
		});
	}

}
