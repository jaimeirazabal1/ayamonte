<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeesDebs2010sTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fees_debs_2010', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('rate')->unsigned();
            $table->integer('year_id')->unsigned()->index();
            $table->foreign('year_id')->references('id')->on('years')->onDelete('restrict');
            $table->integer('month_id')->unsigned()->index();
            $table->foreign('month_id')->references('id')->on('months')->onDelete('restrict');
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
		Schema::drop('fees_debs_2010');
	}

}
