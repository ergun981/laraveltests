<?php

class Cardea_Admin_Cms_Articles_Controller extends AdminBase_Controller {
	
	public function get_index() {
		$articles = CMS\Article::with(array('page_articles','page_articles.page','page_articles.page.page_langs'=>function($query){
			$query->where('lang_id','=','1');
		},'article_langs', 'article_langs.lang'
		))->get();
		$data = array(
			'articles' => $articles
			);
		return view('cardea::admin.cms.articles.list', $data);
	}

	public function get_new($page_id = null)
	{
		if(empty($page_id)) return Redirect::to('admin/cms/articles');
		Asset::container('footer')->add('js-ckeditor','assets/admin/js/lib/ckeditor/ckeditor.js', 'jQuery');

		Asset::container('header')->add('ibutton','assets/admin/js/lib/ibutton/css/jquery.ibutton.css');
		Asset::container('footer')->add('ibuttonjs','assets/admin/js/lib/ibutton/js/jquery.ibutton.beoro.min.js');

		Asset::container('header')->add('datetimepicker','assets/admin/js/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
		Asset::container('footer')->add('datetimepickerjs','assets/admin/js/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');

        Asset::container('header')->add('css-upload', 'assets/admin/js/lib/plupload/js/jquery.plupload.queue/css/plupload-beoro.css');
        Asset::container('footer')->add('js-plupload', 'assets/admin/js/lib/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js', 'jQuery');
        Asset::container('footer')->add('js-plupload-full', 'assets/admin/js/lib/plupload/js/plupload.full.js');
        Asset::container('footer')->add('js-fileplupload', 'assets/admin/js/form/bootstrap-fileupload.min.js', 'jQuery');
        $data = array('page_id'=>$page_id);
        return View::make('admin.cms.articles.new', $data);
    }

    public function post_new()
    {
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

        	$article->name = $form['name'];
        	$article->author_id = Auth::user()->id;
        	$article->publish_on = $form['publish_on'];
        	if(!empty($form['publish_off'])) $article->publish_off = $form['publish_off'];

        	$article->save();

        	//diller döngüye sokularak article-langs eklenecek
        	foreach ($form['lang'] as $id=>$lang) {
               $article_lang = new CMS\Article_Lang;
               $image_ext  = File::extension(Input::file('image.name'));
               if(!empty($image_ext)){                
                $image_path = path('public').'images/articles';
                $image_name = $lang['url'].'.'.$image_ext;
                $image = Input::upload('image', $image_path, $image_name);
                $article_lang->image = $image_name;
                }

            $article_lang->lang_id = $id;
            $article_lang->article_id = $article->id;
            $article_lang->url = $lang['url'];
            $article_lang->title = $lang['title'];
            $article_lang->subtitle = $lang['subtitle'];
            $article_lang->meta_title = $lang['window_title'];
            $article_lang->summary = $lang['summary'];
            $article_lang->content = $lang['content'];
            $article_lang->is_online = isset($form['is_online']) ? $form['is_online'] : '0';
        	$article_lang->is_approve = 1;      //TODO:approve for admin????
            $article_lang->save();
        } 
        	//article_id ve page_id cms_page_articles üzerinden attachlenecek
        $page_article = new CMS\Page_Article;

        $page_article->article_id = $article->id;
        $page_article->page_id = $form['page_id'];
        $page_article->order = DB::table('cms_page_articles')->where('page_id','=',$form['page_id'])->max('order') + 1;
        $page_article->is_online = isset($form['is_online']) ? $form['is_online'] : '0';

        $page_article->save();

        DB::connection()->pdo->commit();

        Session::flash('status_success', 'Makale başarıyla eklendi.');

        return Redirect::back();
    } else {
     DB::connection()->pdo->rollback();
     return Redirect::back()
     ->with_errors($errors)
     ->with_input();
 }
}

public function get_edit($id = null)
{
    $article = CMS\Article::find($id);
    if(empty($id) or empty($article)) return Redirect::to('admin/cms/articles');
    foreach ($article->article_langs  as $article_lang) {
        $langcontents[$article_lang->lang_id] = $article_lang;
    }
        //dd($article);
    Asset::container('footer')->add('js-ckeditor','assets/admin/js/lib/ckeditor/ckeditor.js', 'jQuery');

    Asset::container('header')->add('ibutton','assets/admin/js/lib/ibutton/css/jquery.ibutton.css');
    Asset::container('footer')->add('ibuttonjs','assets/admin/js/lib/ibutton/js/jquery.ibutton.beoro.min.js');

    Asset::container('header')->add('datetimepicker','assets/admin/js/lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
    Asset::container('footer')->add('datetimepickerjs','assets/admin/js/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');


    Asset::container('header')->add('css-upload', 'assets/admin/js/lib/plupload/js/jquery.plupload.queue/css/plupload-beoro.css');
    Asset::container('footer')->add('js-plupload', 'assets/admin/js/lib/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js', 'jQuery');
    Asset::container('footer')->add('js-plupload-full', 'assets/admin/js/lib/plupload/js/plupload.full.js');
    Asset::container('footer')->add('js-fileplupload', 'assets/admin/js/form/bootstrap-fileupload.min.js', 'jQuery');
    $data = array('article' => $article,'langcontents' => $langcontents);
    return View::make('admin.cms.articles.edit', $data);
}

public function post_edit()
{
    $form = Input::all();
    $article = CMS\Article::find($form['article_id']);

        $article->updater_id = Auth::user()->id;
        $article->publish_on = $form['publish_on'];
        if(!empty($form['publish_off'])) $article->publish_off = $form['publish_off'];

        $article->save();

        foreach ($form['lang'] as $id=>$lang)
        {
            if(isset($lang['article_lang_id']))
            {
                $article_lang = CMS\Article_Lang::find($lang['article_lang_id']);
            }
            else 
            {
                $article_lang = new CMS\Article_Lang;
                $article_lang->lang_id = $id;
                $article_lang->article_id = $article->id;
            }

            $image_ext  = File::extension(Input::file('image.name'));
            if(!empty($image_ext)){                
                $image_path = path('public').'images/articles';
                $image_name = $lang['url'].'.'.$image_ext;
                $image = Input::upload('image', $image_path, $image_name);

                if(!empty($article_lang->image))
                {
                    File::delete(path('public').'/images/articles/'.$article_lang->image);
                }

                $article_lang->image = $image_name;
            }
            $article_lang->url = $lang['url'];
            $article_lang->title = $lang['title'];
            $article_lang->subtitle = $lang['subtitle'];
            $article_lang->meta_title = $lang['window_title'];
            $article_lang->summary = $lang['summary'];
            $article_lang->content = $lang['content'];
            $article_lang->is_online = isset($form['is_online']) ? $form['is_online'] : '0';
            $article_lang->is_approve = 1; //TODO:approve for admin????

            $article_lang->save();
        }

        $page_article = CMS\Page_Article::where('article_id','=',$article->id)->first();
        $page_article->is_online = isset($form['is_online']) ? $form['is_online'] : '0';
        $page_article->save();

        Session::flash('status_success', 'Makale başarıyla güncellendi.');
        return Redirect::back();
    }

    public function get_delete($id = null)
    {
        $article = CMS\Article::find($id);
        if(empty($id) or empty($article)) return Redirect::to('admin/cms/articles');
        $article->delete_all();
        Session::flash('status_success', 'Makale başarıyla silindi.');
        return Redirect::to('admin/cms/articles');
    }

}