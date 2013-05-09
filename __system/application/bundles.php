<?php

return array(

	'cardea' => array( 
		'auto' => true,
		'handles' => 'cardea',
		'depencies' => array('auth', 'media', 'ecommerce', 'cms'),
		),
	'auth' => array( 
		'auto' => true,
		'handles' => 'auth',
		'type' => 'auth',
		'title' => 'Oturum',
		'link' => '',
		'navs'  => array(
			'Kullanıcılar' => 'users',
			),
		),
	'cms' => array( 
		'auto' => true,
		'handles' => 'cms',
		'type' => 'cms',
		'title' => 'İçerik Yönetimi',
		'link' => '',
		'navs'  => array(
			'Menüler' => 'menus',
			'Yeni Sayfa' => 'pages@new',
			'Makaleler' => 'articles',
			),
		),
	'elfinder' => array( 
		'auto' => true,
		'handles' => 'elfinder',
		'type' => 'media',
		'title' => 'Media Galeri',
		'link' => '',
		'navs'  => array(
			'ElFinder' => 'explorer@index',
			),
		),

	);
