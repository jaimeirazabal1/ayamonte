<?php

class MonthsTableSeeder extends Seeder {

	public function run()
	{
        Month::create(['name' => 'Enero']);
        Month::create(['name' => 'Febrero']);
        Month::create(['name' => 'Marzo']);
        Month::create(['name' => 'Abril']);
        Month::create(['name' => 'Mayo']);
        Month::create(['name' => 'Junio']);
        Month::create(['name' => 'Julio']);
        Month::create(['name' => 'Agosto']);
        Month::create(['name' => 'Septiembre']);
        Month::create(['name' => 'Octubre']);
        Month::create(['name' => 'Noviembre']);
        Month::create(['name' => 'Diciembre']);
	}

}