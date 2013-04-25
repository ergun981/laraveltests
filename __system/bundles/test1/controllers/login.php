<?php

class Auth_Login_Controller extends Controller {
	public $restful = true;

	public function __construct(){

		Asset::container('header')->add('logincss','assets/admin/css/login.css');
		Asset::container('header')->add('jQuery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
		Asset::container('header')->add('jquery_validation','assets/admin/js/lib/jquery-validation/jquery.validate.js','jQuery');

		Asset::container('header')->add('jquery_validationtr','assets/admin/js/lib/jquery-validation/localization/messages_tr.js','jquery_validation');   
	}

	public function get_index()
	{
		if (Auth::check() && Auth::user()->has_role('admin')) return Redirect::to('cardea::dashboard');
		return view('auth::login');
	}

	public function post_attempt()
	{
		$creds = array(
			'username' 	 => Input::get('email'),
			'password'   => Input::get('password'),
			'activated'  => 1
			);
		// make sure vars have values
		if (empty($creds['username']) or empty($creds['password']))
		{
			Session::flash('status_error', 'Giriş bilgileri eksik.');
			return Redirect::back();
		}
		$user_attempt = new Attempt($creds['username']);

		// unsuspend durumunda
		if (!$user_attempt->is_suspend())		
		{
			// if attempts is bigger than or eq to limit - suspend the login/ip combo
			if ($user_attempt->get_limit() <= $user_attempt->get())
			{
				$user_attempt->suspend();
				Session::flash('status_error', 'Giriş deneme sayısı kalmadı. Lütfen 5 dakika bekleyin.');
			}
			if (!$user_attempt->is_suspend())		
			{
				// if user is validated
				if ( Auth::attempt($creds) ) {
					$user_attempt->clear();
					//save_log(Auth::user()->id, 'login'); //daha sonra ...
					//if($_SERVER['HTTP_REFERER']) return Redirect::to($_SERVER['HTTP_REFERER']);
					$referer = Session::get('referer', '/');
					Session::forget('referer');
					return Redirect::to($referer);
					//header("Location: $referer");
				} else {
					$user_attempt->add();
					Input::flash();
					//check Activation
					if ( User::where_email_and_activated(Input::get('email'), 0)->first() ) {
						Session::flash('status_error', 'Lütfen hesabınızı aktif edin.');
						return Redirect::back();
					} else {
						Session::flash('status_error', 'Giriş bilgileriniz geçersiz gözüküyor. Lütfen tekrar deneyin.');
						return Redirect::back();
					}
				}
			}
		}
		//suspend durumunda
		$user_attempt->add();
		Session::flash('status_error', 'Giriş deneme sayısı kalmadı. Lütfen 5 dakika bekleyin.');
		return Redirect::back();
	}
	
	public function get_logout()
	{
		Auth::logout();

		return Redirect::back();
	}
}