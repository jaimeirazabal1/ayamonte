<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('lot_id')->unsigned()->index();
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('restrict');
            $table->integer('month_id')->unsigned()->index();
            $table->foreign('month_id')->references('id')->on('months')->onDelete('restrict');
            $table->integer('year_id')->unsigned()->index();
            $table->foreign('year_id')->references('id')->on('years')->onDelete('restrict');
            $table->enum('transaction', [
                'fee_ordinary_maintenance',
                'fee_extraordinary_reserve',
                'fee_special_work',
                'fee_debts_2010'
            ]);
            $table->integer('indebted_months')->unsigned()->default(0); # Meses en deuda
            $table->integer('indebted_amount')->unsigned()->default(0); # Intereses por morosidad
            $table->decimal('monthly_interest', 6, 2)->default(0);
            $table->integer('amount')->unsigned()->default(0); # Monto de adeudo
            $table->integer('balance')->unsigned()->default(0); # Monto de adeudo + Intereses moratorios
            $table->integer('balance_partial')->unsigned()->default(0); # Monto de adeudo final cuando el pago o abono no cubre el total de la deuda
            $table->enum('status', ['pending', 'paid', 'current', 'partially_paid'])->default('pending');
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
		Schema::drop('transactions');
	}

}
