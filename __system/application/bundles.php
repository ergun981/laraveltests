<?php

return array(

	'cardea' => array( 
		'auto' => true,
		'handles' => 'cardea',
		'depencies' => array('auth', 'media', 'ecommerce'),
		),
	'auth' => array( 
		'auto' => true,
		'handles' => 'auth',
		'type' => 'auth',
		'title' => 'Oturum',
		),
	'test1' => array( 
		'auto' => true,
		'handles' => 'test1',
		'type' => 'media',
		'title' => 'Media Galeri'
		),
	'test2' => array( 
		'auto' => true,
		'handles' => 'test2',
		'type' => 'ecommerce',
		'title' => 'E- Ticaret',
		),


	);
