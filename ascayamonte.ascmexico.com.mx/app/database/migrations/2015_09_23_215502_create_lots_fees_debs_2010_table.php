<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLotsFeesDebs2010Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lots_fees_debs_2010', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('lot_id')->unsigned()->index();
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('restrict');
            $table->integer('rate')->unsigned();
            $table->integer('amount')->unsigned()->default(0);
            $table->tinyInteger('num_payment');
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
		Schema::drop('lots_fees_debs_2010');
	}

}
