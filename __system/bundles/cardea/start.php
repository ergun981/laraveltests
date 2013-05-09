<?php

Autoloader::map(array(
	'CardeaBase_Controller' => path('bundle').'cardea/controllers/cardeabase.php',
));
Autoloader::directories(array(
	path('bundle').'cardea'.DS.'models',
	path('bundle').'cardea'.DS.'libraries',
));

require path('bundle').'cardea/helpers'.EXT;
