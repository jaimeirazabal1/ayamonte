<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatusBalanceAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audit_cronjob_balances', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('lot_id')->unsigned()->index();
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('restrict');
            $table->integer('month')->unsigned();
            $table->integer('year')->unsigned();
            $table->enum('status', ['successful', 'error'])->default('error');
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
		Schema::drop('audit_cronjob_balances');
	}

}
