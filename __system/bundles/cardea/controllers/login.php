<?php

class Cardea_Login_Controller extends AdminBase_Controller {
	

	public function __construct(){

		Asset::container('header')->add('logincss','assets/admin/css/login.css');
		Asset::container('header')->add('jQuery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
		Asset::container('header')->add('jquery_validation','assets/admin/js/lib/jquery-validation/jquery.validate.js','jQuery');

		Asset::container('header')->add('jquery_validationtr','assets/admin/js/lib/jquery-validation/localization/messages_tr.js','jquery_validation');   
	}

	public function get_index()
	{
		if (Auth::check() && Auth::user()->has_role('admin')) return Redirect::to('cardea::admin/dashboard');
		return view('cardea::admin.login');
	}

}