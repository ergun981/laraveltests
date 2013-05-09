<?php

	Autoloader::map(array(
		'CmsBase_Controller' => path('bundle').'cms/controllers/cmsbase.php',
	));
	
	Autoloader::directories(array(
		path('bundle').'cms'.DS.'models',
	));