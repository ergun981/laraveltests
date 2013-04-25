<?php
/* APPLICATION ROUTES */

// SET SEGMENTS LIMIT
Router::$segments = 10;

//APPLICATION ROUTES

Route::get('(.*)', array('before' => 'init', function($url) {

}));

//APPLICATION CONTROLLERS

Route::controller(Controller::detect());


//APPLICATION FILTER FRONTEND

Route::filter('init', function()
{

	//SAVE SESSION CREDENTIAL
	if(Auth::check()) {

		Session::put('USERID', Auth::user()->id);
		Session::put('USERNAME', Auth::user()->full_name());
		Session::put('EMAIL', Auth::user()->email);		
		Session::put('ROLEID', Auth::user()->role_id);
		Session::put('ROLE', Auth::user()->role_name());
		Session::put('USERLANG', Auth::user()->lang);

	} else {

		Session::put('USERID', 0);
		Session::put('USERNAME', '');
		Session::put('EMAIL', '');		
		Session::put('ROLEID', 0);
		Session::put('ROLE', 0);
		Session::put('USERLANG', Config::get('application.language'));

	}

	// //LOAD SEGMENTS
	// $segment = CmsUtility::url_segments();

	// //SEGMENTS SLUG CONSTANT	
	// define('SLUG_FULL', $segment['full']);
	// define('SLUG_FIRST', $segment['first']);
	// define('SLUG_LAST', $segment['last']);
	// define('SLUG_BACK', $segment['first']);
	// // BOOLEAN
	// define('SLUG_PREVIEW', $segment['preview']);

	//GLOBAL CONSTANT
	define('SITE_URL', Config::get('application.url'));
	define('SITE_USERID', Session::get('USERID', 0));
	define('SITE_USERNAME', Session::get('USERNAME', ''));
	define('SITE_EMAIL', Session::get('EMAIL', ''));
	define('SITE_ROLEID', Session::get('ROLEID', 0));
	define('SITE_ROLE', Session::get('ROLE', 0));
	define('SITE_LANG', Session::get('SITE_LANG', Config::get('application.language')));
	//define('SITE_HOMEPAGE', CmsUtility::home_page());

	//define('THEME', Config::get('cms::settings.theme'));

	//SET LOCALE

	setlocale(LC_ALL, Config::get('cms::settings.locale.'.SITE_LANG), Config::get('cms::settings.locale.'.SITE_LANG).'.utf8');
	dd(SITE_URL);

});
//for test
Route::get('(:bundle)/sayfa-olustur', array('before' => 'auth','as'=>'new_page', 'uses'=>'cardea::cms.pages@new'));
Route::get('(:bundle)/menuler', array('before' => 'auth','as'=>'menus', 'uses'=>'cardea::cms.menus@index'));
Route::get('(:bundle)/makaleler', array('before' => 'auth','as'=>'list_articles', 'uses'=>'cardea::cms.articles@index'));

Route::get('/test', function(){
	return phpinfo();
});

Route::get('/', function()
{
	dd(Bundle::all());
	//test
	return 'Hoşgeldiniz falan siteye...Admin Panel için '. HTML::link(action("auth::login"), "tıklayınız").'.';
});
Route::controller(Controller::detect('cardea'));
Route::controller(Controller::detect('auth'));

Route::get('(:bundle)',array('before'=>'auth', 'uses'=>'cardea::static@dashboard'));
// Route::get('(:bundle)', array('uses' => 'cardea::login@index'));
Route::get('(:bundle)/dashboard', 	array('before' => 'auth', 'uses' => 'cardea::static@dashboard'));
Route::get('(:bundle)/faq', 	array('before' => 'auth', 'as'=>'faq','uses' => 'cardea::static@faq'));

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application. The exception object
| that is captured during execution is then passed to the 500 listener.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function($exception)
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
    if (Auth::guest()) {
        Session::put('referer', URL::current());
        return Redirect::to_action('auth::login');
    }
});