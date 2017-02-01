<?php

class UserTableSeeder extends Seeder {

	public function run()
	{
        // Crea el grupo
        $adminGroup = Sentry::createGroup(array(
            'name'        => 'Administrador',
            'permissions' => array(
                'admin' => 1,
            ),
        ));


        // Crea el usuario
        $user = Sentry::createUser(array(
            'first_name' => 'Desarrollo',
            'last_name'  => 'ASC',
            'email'     => 'ventas@ascmexico.com',
            'password'  => 'ventas123',
            'activated' => true,
        ));

        // Find the group using the group id
        $adminGroup = Sentry::findGroupById(1);

        // Assign the group to the user
        $user->addGroup($adminGroup);
	}

}