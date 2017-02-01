<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lots', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('reference', 20)->unique();
			$table->integer('official_number');
			$table->decimal('m2', 6, 2);
			$table->string('cadastral_key', 40)->nullable();
			$table->string('owner');
			$table->string('lot', 10);
			$table->string('account_number', 20);
			$table->integer('square_id')->unsigned();
			$table->timestamps();

            $table->foreign('square_id')->references('id')->on('squares')->onDelete('restrict');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lots');
	}

}
