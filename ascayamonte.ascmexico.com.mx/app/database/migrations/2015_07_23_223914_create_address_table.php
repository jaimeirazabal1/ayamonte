<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('address', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('address');
            $table->string('neighborhood', 50);
            $table->string('zipcode', 10);
            $table->string('city', 50);
            $table->string('state', 50);
            $table->integer('lot_id')->unsigned();
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('restrict');
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
		Schema::drop('address');
	}

}
