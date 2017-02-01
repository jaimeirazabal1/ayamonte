<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_transaction', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payment_id')->unsigned()->index();
			$table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
			$table->integer('transaction_id')->unsigned()->index();
			$table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_transaction');
	}

}
