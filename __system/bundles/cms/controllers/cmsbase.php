<?php
class CmsBase_Controller extends CardeaBase_Controller {

	public $menus;
	public $languages;
	public $pages;

	public function __construct(){
// ///////////////////////////////////////////////////////		
		if (! Cache::has('menus')){
			$this->menus  =  Menu::with(array('pages' => function($query){$query->order_by('order');}, 'pages.page_langs' => function($query){
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
			$this->languages  =  Language::where('is_online',"=","1")->get();
			Cache::put('languages',$this->languages, 1);
		}
		else{ $this->languages = Cache::get('languages'); }
			$this->languages  =  Language::where('is_online',"=","1")->get();
		
		View::share('languages', $this->languages);

// ///////////////////////////////////////////////////////
		
		parent::__construct();
	}
}