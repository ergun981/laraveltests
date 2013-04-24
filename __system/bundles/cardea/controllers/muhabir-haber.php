<?php
class Muhabir_Haber_Controller extends Base_Controller {
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->filter('before', 'auth');
	}

	public function get_index() {
		$authors = User\Role::find(3)->users()->get();
		$author_article_count = array();
		foreach ($authors as $author) {
			$author_article_count[$author->id] = CMS\Article::where('author_id','=',$author->id)->where_is_approved('1')->count();
		}
		$data = array(
			'authors' => $authors,
			'author_article_count' => $author_article_count);
		return View::make('zoneportal.muhabir.index',$data);
	}

	public function get_new() {
		Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
		return view('zoneportal.muhabir.haber');
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
        DB::connection()->pdo->beginTransaction();
        $errors = CMS\Article::validate($form);

        if (!$errors) {
        	$article = new CMS\Article;

        	$article->name = Str::slug($form['title']);
        	$article->author_id = Auth::user()->id;
        	$article->save();

        	$haber = new CMS\Article_Lang;
        	$haber->article_id = $article->id;
        	$haber->title = $form['title'];
        	$haber->url = Str::slug($form['title']);
        	$haber->subtitle = $form['title'];		
        	$haber->meta_title = $form['title'];
        	$haber->summary = $form['summary'];
        	$haber->content = $form['content'];
        	$haber->lang_id = '1';
        	$haber->is_online = '0';
        	$haber->is_approve = '0';

        	$haber->save();

        	$page_article = new CMS\Page_Article;

        	$page_article->article_id = $article->id;
        	$page_article->page_id = '3';
        	$page_article->order = DB::table('cms_page_articles')->where('page_id','=','3')->max('order') + 1;
        	$page_article->is_online = '0';
        	$page_article->save();

        	DB::connection()->pdo->commit();

        	Session::flash('status_success', 'Haber başarıyla eklendi.Yönetici onayından sonra aktif olacaktır!');

        	return Redirect::to('muhabir-haber');
        } else {
        	DB::connection()->pdo->rollback();
        	return Redirect::back()
        	->with_errors($errors)
        	->with_input();
        }


    }

    public function get_author($id)
    {
    	$author = User::find($id);
    	if(empty($author) or !$author->has_role('editor')) return Redirect::to('muhabir-haber');

    	$articles = CMS\Article::with(array('page_articles', 'article_langs'))->where('author_id','=',$id)->order_by('created_at','desc')->get();

    	$article_lang_list = array();
    	foreach($articles as $article)
    	{
    		if(($article->article_langs[0]->is_approve == '1') && ($article->page_articles[0]->page_id == 3))
    			$article_lang_list[] = $article->article_langs[0];
    	}
    	$data = array('author' => $author,
    		'articles' => $article_lang_list);
    	return View::make('zoneportal.muhabir.muhabir', $data);

    }

    public function get_apply()
    {
    	if(!Auth::user()->has_role('user')) return "Üyelik seviyeniz bu işlem için uygun değil.";
    	if(Activity::where('user_id','=',Auth::user()->id)->where('action_id','=','19')->count()>0) return "İncelenmeyi bekleyen bir başvurunuz bulunuyor.";
    	save_log(Auth::user()->id, 'apply-for-author');
    	return "Başvurunuz Kaydedildi";
    }

}