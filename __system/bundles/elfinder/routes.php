<?php
	//Route::controller(Controller::detect('elfinder'));
	Route::get('(:bundle)', function(){	
		return Redirect::to_action('elfinder::explorer');
	});