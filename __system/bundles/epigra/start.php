<?php

Autoloader::map(array(
	'Base_Controller' => path('bundle').'epigra/controllers/base.php',
	'AdminBase_Controller' => path('bundle').'epigra/controllers/adminbase.php',
));
Autoloader::directories(array(
	path('bundle').'epigra'.DS.'models',
	path('bundle').'epigra'.DS.'libraries',
	path('bundle').'epigra'.DS.'models'.DS.'cms',
));

Event::listen(View::loader, function($bundle, $view)
{
	return View::file($bundle, $view, Bundle::path($bundle).'views');
});

require path('bundle').'epigra/helpers'.EXT;
