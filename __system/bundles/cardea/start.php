<?php

Autoloader::map(array(
	'Cardea_AdminBase_Controller' => path('bundle').'cardea/controllers/adminbase.php',
));
Autoloader::directories(array(
	path('bundle').'cardea'.DS.'models',
	path('bundle').'cardea'.DS.'libraries',
	path('bundle').'cardea'.DS.'models'.DS.'cms',
));

Event::listen(View::loader, function($bundle, $view)
{
	return View::file($bundle, $view, Bundle::path($bundle).'views');
});

require path('bundle').'cardea/helpers'.EXT;
