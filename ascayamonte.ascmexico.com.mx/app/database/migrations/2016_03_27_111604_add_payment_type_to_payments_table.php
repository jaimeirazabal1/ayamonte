<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPaymentTypeToPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payments', function(Blueprint $table)
		{
			$table->enum('type', ['cash', 'card', 'transfer', 'cheque'])->default('cash');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payments', function(Blueprint $table)
		{
			$table->dropColumn('type');
		});
	}

}
