<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddBalancePositiveToLotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lots', function(Blueprint $table)
		{
			$table->integer('balance_positive')->unsigned()->default(0);
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
            $table->dropColumn('balance_positive');
		});
	}

}
