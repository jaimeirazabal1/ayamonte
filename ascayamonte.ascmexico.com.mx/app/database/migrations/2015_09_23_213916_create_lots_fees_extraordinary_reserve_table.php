<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLotsFeesExtraordinaryReserveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lots_fees_extraordinary_reserve', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('lot_id')->unsigned()->index();
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('restrict');
            $table->integer('rate')->unsigned();
            $table->tinyInteger('number_payment');
            $table->integer('amount')->unsigned()->default(0);
            $table->integer('month_id')->unsigned()->index();
            $table->foreign('month_id')->references('id')->on('months')->onDelete('restrict');
            $table->integer('year_id')->unsigned()->index();
            $table->foreign('year_id')->references('id')->on('years')->onDelete('restrict');
            $table->enum('status', ['pending', 'paid'])->default('pending');
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
		Schema::drop('lots_fees_extraordinary_reserve');
	}

}
