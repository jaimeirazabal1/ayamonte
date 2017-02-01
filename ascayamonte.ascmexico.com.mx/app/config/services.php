<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => 'sandbox85e72c20649449d189d68045bf8cedd4.mailgun.org',
		'secret' => 'key-77f5bc8c00ecead6ff3800b46bce5559',
	),

	'mandrill' => array(
		'secret' => getenv('services.mandrill.apikey'),
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

);
