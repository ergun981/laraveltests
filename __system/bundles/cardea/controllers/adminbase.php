<?php
class Cardea_AdminBase_Controller extends Controller {

	public $restful = true;
	public $menus;
	public $languages;
	public $pages;
	public $c_olgu, $c_muhabir, $c_haber, $c_forum;

	public function __construct(){
		$this->filter('before', 'adminauth');
		//header -> styles
		Asset::container('header')->add('css-bootstrap.min', 'assets/admin/bootstrap/css/bootstrap.css');
		Asset::container('header')->add('css-bootstrap-responsive.min', 'assets/admin/bootstrap/css/bootstrap-responsive.min.css', 'css-bootstrap.min');
		Asset::container('header')->add('css-aristo', 'assets/admin/js/lib/jquery-ui/css/Aristo/Aristo.css', 'css-bootstrap.min');
		Asset::container('header')->add('css-iconSweet2', 'assets/admin/img/icsw2_16/icsw2_16.css', 'css-bootstrap.min');
		Asset::container('header')->add('css-splashy', 'assets/admin/img/splashy/splashy.css', 'css-bootstrap.min');
		Asset::container('header')->add('css-flags', 'assets/admin/img/flags/flags.css', 'css-bootstrap.min');
		Asset::container('header')->add('css-jq.powertip', 'assets/admin/js/lib/powertip/jquery.powertip.css');
		Asset::container('header')->add('css-beoro', 'assets/admin/css/beoro.css');
		Asset::container('header')->add('css-sticky', 'assets/admin/js/lib/sticky/sticky.css');

		//header -> scripts
		//Asset::container('header')->add('jQuery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
		Asset::container('header')->add('jQuery', 'assets/admin/js/jquery.min.js');

		//footer -> scripts
		Asset::container('footer')->add('js-bootstrap.min', 'assets/admin/bootstrap/js/bootstrap.min.js','jQuery');
		Asset::container('footer')->add('js-jq.fademenu', 'assets/admin/js/jquery.fademenu.js','jQuery');
		Asset::container('footer')->add('js-selectnav', 'assets/admin/js/selectnav.min.js');
		Asset::container('footer')->add('js-jq.actual', 'assets/admin/js/jquery.actual.min.js', 'jQuery');
		Asset::container('footer')->add('js-jq.easing', 'assets/admin/js/jquery.easing.1.3.min.js', 'jQuery');
		Asset::container('footer')->add('js-jq.powertip', 'assets/admin/js/lib/powertip/jquery.powertip-1.1.0.min.js', 'jQuery');
		Asset::container('footer')->add('js-moment', 'assets/admin/js/moment.min.js');
		Asset::container('footer')->add('js-beoro', 'assets/admin/js/beoro_common.js');
		Asset::container('footer')->add('js-sticky', 'assets/admin/js/lib/sticky/sticky.min.js');


// ///////////////////////////////////////////////////////		
		if (! Cache::has('menus')){
			$this->menus  =  CMS\Menu::with(array('pages' => function($query){$query->order_by('order');}, 'pages.page_langs' => function($query){
				 $query->where('lang_id', '=', '1');
			} ))->get();
			Cache::put('menus',$this->menus, 1);
			//
			//(array('posts' => function($query)

		}
		else{ $this->menus = Cache::get('menus'); }

		View::share('menus', $this->menus);

// ///////////////////////////////////////////////////////
		if (! Cache::has('languages')){
			$this->languages  =  CMS\Language::where('is_online',"=","1")->get();
			Cache::put('languages',$this->languages, 1);
		}
		else{ $this->languages = Cache::get('languages'); }
		
		View::share('languages', $this->languages);

// ///////////////////////////////////////////////////////
		if (! Cache::has('c_olgu')){
			$this->c_olgu
			  =  QA\Question::where('is_approved','=','0')->count();
			Cache::put('c_olgu',$this->c_olgu, 1);

		}
		else{ $this->c_olgu = Cache::get('c_olgu'); }

		View::share('c_olgu', $this->c_olgu);

// ///////////////////////////////////////////////////////		
		if (! Cache::has('c_forum')){
			$this->c_forum
			  =  Forum\Topic::where_is_approved('0')->count();
			Cache::put('c_forum',$this->c_forum, 1);

		}
		else{ $this->c_forum = Cache::get('c_forum'); }

		View::share('c_forum', $this->c_forum);

// ///////////////////////////////////////////////////////
		if (! Cache::has('c_muhabir')){
			$this->c_muhabir = Activity::where('action_id','=','19')->where('points', '<=' ,'0')->count();
			Cache::put('c_muhabir',$this->c_muhabir, 1);

		}
		else{ $this->c_muhabir = Cache::get('c_muhabir'); }

		View::share('c_muhabir', $this->c_muhabir);

// ///////////////////////////////////////////////////////
		if (! Cache::has('c_haber')){
			$this->c_haber = CMS\Page_Article::with(array('article' => function($query){$query->where_is_approved('0');}))->where_page_id_and_is_online(3, 0)->count();
			Cache::put('c_haber',$this->c_haber, 1);

		}
		else{ $this->c_haber = Cache::get('c_haber'); }

		View::share('c_haber', $this->c_haber);
		
		parent::__construct();

// ///////////////////////////////////////////////////////

	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}