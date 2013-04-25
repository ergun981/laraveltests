<?php

Autoloader::map(array(
	'CardeaBase_Controller' => path('bundle').'cardea/controllers/cardeabase.php',
));
Autoloader::directories(array(
	path('bundle').'cardea'.DS.'models',
	path('bundle').'cardea'.DS.'libraries',
	path('bundle').'cardea'.DS.'models'.DS.'cms',
));

require path('bundle').'cardea/helpers'.EXT;
