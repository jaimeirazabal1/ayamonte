<?php


/**
 * Class SquaresTableSeeder
 */
class SquaresTableSeeder extends Seeder {

    /**
     *
     */
    public function run()
	{
        Square::create([
            'name' => 'Literatura'
        ]);
        Square::create([
            'name' => 'Música'
        ]);
        Square::create([
            'name' => 'Pintura'
        ]);
        Square::create([
            'name' => 'Escultura'
        ]);
        Square::create([
            'name' => 'Cine'
        ]);
        Square::create([
            'name' => 'Danza'
        ]);
        Square::create([
            'name' => 'Arquitectura'
        ]);
	}

}