<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPaymentFieldsToPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payments', function(Blueprint $table)
		{
			$table->string('payment_reference_type', 20);
            $table->text('comments')->nullable();
            #$table->string('folio', 30)->unique(); // El folio es el ID
            $table->string('concept', 100)->nullable(); // Pagos de otros conceptos diferentes a abonos
            $table->enum('payment_type_concept', ['other', 'lots']); // Pagos de otros conceptos o pago de deudas de condominios
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
			$table->dropColumn('payment_reference_type');
            $table->dropColumn('comments');
            #$table->dropColumn('folio');
            $table->dropColumn('concept');
            $table->dropColumn('payment_type_concept');
		});
	}

}
