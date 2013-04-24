<?php

class Cardea_Cms_Pages_Controller extends AdminBase_Controller {

	public function __construct(){
		parent::__construct();

		Asset::container('header')->add('ibutton','assets/admin/js/lib/ibutton/css/jquery.ibutton.css');
		Asset::container('footer')->add('ibuttonjs','assets/admin/js/lib/ibutton/js/jquery.ibutton.beoro.min.js');

		foreach($this->languages as $lang){
			$lang_dropdown[$lang->id] = $lang->title;
		}
		foreach($this->menus as $menu){
			$menu_dropdown[$menu->id] = $menu->title;
		}

		$pages_dropdown = array_merge(array(-1 => '---'), CMS\Page_Lang::where('lang_id','=',1)->lists('title', 'id'));

		View::share('lang_dd', $lang_dropdown);
		View::share('menu_dd', $menu_dropdown);
		View::share('pages_dd', $pages_dropdown);

	}

	public function get_index() {
		$data = array();
		return "yok öyle bişey";
		return view('admin.cms.pages.list', $data);
	}

	public function get_new() {
		

		$data = array();
		return view('cardea::admin.cms.pages.new', $data);
	}

	public function post_new() {
		/**
		 * Get all input
		 * @var array
		 */
		$form = Input::all();
		/**
		 * Validate all form data
		 * @var mixed
		 */
		$errors = CMS\Page::validate($form);

		if (!$errors) {
			//$current_user = Auth::user()->id;
			$current_user = 1;
			$page = new CMS\Page;

			$page->menu_id = $form['menu_id'];
			$page->parent_id = $form['parent_id'];
			$page->name = $form['name'];

			//$page->author_id = isset($form['author_id']) ? $form['author_id'] : $current_user;
			//
			// TODO: Current User ID
			$page->author_id = 1;

			//$page->updater_id = isset($form['updater_id']) ? $form['updater_id'] : $current_user;
			//
			// TODO: Current User ID
			$page->updater_id = 1;
			
			$page->is_online = isset($form['is_online']) ? $form['is_online'] : '0';
			$page->order = isset($form['order']) ? $form['order'] : 1;
			$page->publish_on = isset($form['publish_on']) ? $form['publish_on'] : new DateTime;
			$page->publish_off = isset($form['publish_off']) ? $form['publish_off'] : NULL;
			
			/*
			DB::connection()->pdo->beginTransaction();
			DB::connection()->pdo->commit();
			DB::connection()->pdo->rollback();
			*/
			$page->save();

			foreach ($this->languages as $lang):
				$content            = new CMS\Page_Lang;
				$content->lang_id   = $lang->id;
				$content->page_id   = $page->id;
				$content->url       = $form['url'][$lang->id];
				$content->title     = $form['title'][$lang->id];
				$content->subtitle  = $form['subtitle'][$lang->id];
				$content->subtitle  = $form['subtitle'][$lang->id];
				$content->nav_title = $form['nav_title'][$lang->id];
				$content->meta_title = $form['meta_title'][$lang->id];
				$content->is_online = isset($form['is_online']) ? $form['is_online'] : '0';
				$content->save();
			endforeach;

			Session::flash('status_success', 'Added page #' . $page->id);

			return Redirect::to('admin/dashboard');
		} else {
			return Redirect::to('admin/cms/pages/new')
							->with_errors($errors)
							->with_input();
		}
	}


	public function get_edit($id = NULL) {
		Asset::container('header')->add('datetimepicker','assets/admin/js/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
		Asset::container('footer')->add('datetimepickerjs','assets/admin/js/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
		
		$page = CMS\Page::with(array('page_langs','page_articles','page_articles.article','page_article.article.article_langs'=>function($query){
			$query->where('lang_id','=','1');
			}))->find($id);
		if(!is_null($page)) {
			$data = array(
				'page' => $page
				);

		return view('admin.cms.pages.edit', $data);		
		}
		return Redirect::back();
	}

	public function post_update_details($page_id = NULL) {
		$posted = Input::all();
		$page = CMS\Page::find($page_id);
		if(!is_null($page)) {
			$page->fill($posted);
			$page->is_online = isset($posted['is_online']) ? $posted['is_online'] : '0';
			$page->save();
			Session::flash('status_success', 'Sayfa başarıyla güncellendi.');
			return Redirect::back();
		}
		Session::flash('status_error', 'Belirtilen sayfa bulunamadi!');
		return Redirect::back();
	}
	public function post_update_langs($page_id = NULL) {
		$posted = Input::all();
		foreach ($posted as $key => $value) {
			$lang_id = (int)substr($key, 1);
			$pagelang = CMS\Page_Lang::where('page_id', '=', $page_id)
										->where('lang_id', '=', $lang_id)
										->first();
			if(!is_null($pagelang)) {
				$pagelang->fill($value)
						 ->save();

			Session::flash('status_success', 'Sayfa başarıyla güncellendi.');
			return Redirect::back();
			}
		}
		Session::flash('status_error', 'Belirtilen sayfa bulunamadi!');
		return Redirect::back();
	}

}