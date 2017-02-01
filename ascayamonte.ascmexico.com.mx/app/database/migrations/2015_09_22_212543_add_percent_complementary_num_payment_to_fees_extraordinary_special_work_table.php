<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPercentComplementaryNumPaymentToFeesExtraordinarySpecialWorkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fees_extraordinary_special_work', function(Blueprint $table)
		{
			$table->float('percent')->default(0);
            $table->tinyInteger('num_payment');
            $table->boolean('complementary')->default(false);
            $table->integer('mount')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('fees_extraordinary_special_work', function(Blueprint $table)
		{
			$table->dropColumn('percent');
            $table->dropColumn('num_payment');
            $table->dropColumn('complementary');
            $table->dropColumn('mount');
		});
	}

}
