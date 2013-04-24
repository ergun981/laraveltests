<?php

class Admin_Muhabir_Haber_Controller extends AdminBase_Controller
{
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
	}

//TODO : muhabir ve haber onay  listelemleri
	public function get_index()
	{
		Asset::container('header')->add('beoro-datatable-css','assets/admin/js/lib/datatables/css/datatables_beoro.css');
		Asset::container('header')->add('beoro-tabletools-css','assets/admin/js/lib/datatables/extras/TableTools/media/css/TableTools.css');

		Asset::container('footer')->add('beoro-datatable-js1','assets/admin/js/lib/datatables/js/jquery.dataTables.min.js');
		Asset::container('footer')->add('beoro-datatable-js2','assets/admin/js/lib/datatables/extras/ColReorder/media/js/ColReorder.min.js');
		Asset::container('footer')->add('beoro-datatable-js3','assets/admin/js/lib/datatables/extras/ColVis/media/js/ColVis.min.js');
		Asset::container('footer')->add('beoro-datatable-js4','assets/admin/js/lib/datatables/extras/TableTools/media/js/TableTools.min.js');
		Asset::container('footer')->add('beoro-datatable-js5','assets/admin/js/lib/datatables/extras/TableTools/media/js/ZeroClipboard.js');
		Asset::container('footer')->add('beoro-datatable-js6','assets/admin/js/lib/datatables/js/jquery.dataTables.bootstrap.min.js');
		$haberler = CMS\Page_Article::with(array('article', 'article.article_langs','article.article_langs.lang', 'article.author'))->where_page_id(3)->get();
		$data = array('haberler' => $haberler);
		return view('admin.muhabir.list', $data);
	}

	public function get_unapproved_authors()
	{
		Asset::container('header')->add('beoro-datatable-css','assets/admin/js/lib/datatables/css/datatables_beoro.css');
		Asset::container('header')->add('beoro-tabletools-css','assets/admin/js/lib/datatables/extras/TableTools/media/css/TableTools.css');

		Asset::container('footer')->add('beoro-datatable-js1','assets/admin/js/lib/datatables/js/jquery.dataTables.min.js');
		Asset::container('footer')->add('beoro-datatable-js2','assets/admin/js/lib/datatables/extras/ColReorder/media/js/ColReorder.min.js');
		Asset::container('footer')->add('beoro-datatable-js3','assets/admin/js/lib/datatables/extras/ColVis/media/js/ColVis.min.js');
		Asset::container('footer')->add('beoro-datatable-js4','assets/admin/js/lib/datatables/extras/TableTools/media/js/TableTools.min.js');
		Asset::container('footer')->add('beoro-datatable-js5','assets/admin/js/lib/datatables/extras/TableTools/media/js/ZeroClipboard.js');
		Asset::container('footer')->add('beoro-datatable-js6','assets/admin/js/lib/datatables/js/jquery.dataTables.bootstrap.min.js');
		$unapproved_authors = Activity::where('action_id','=','19')->where('points', '<=' ,'0')->get();
		$data = array('activities' => $unapproved_authors);
		return view('admin.muhabir.applies', $data);
	}

	public function get_approve_author($act_id)
	{
		$activity = Activity::find($act_id);
		$activity->user->role_id = 3;
		$activity->user->points +=  Config::get('project.action_points')[$activity->action_id];
		$activity->user->save();
		$activity->points = Config::get('project.action_points')[$activity->action_id];
		$activity->save();
        Session::flash('status_success', $activity->user->full_name().', muhabir başvurusu onaylandı.');
        return Redirect::back();

	}

	public function get_approve($id)
	{
		$article = CMS\Article::with(array('article_langs', 'page_articles'))->find($id);
		$article->is_approved = '1';
		$act_lang = $article->article_langs()->first();
		$act_lang->is_online = '1';
		$act_lang->is_approve = '1';
		$act_lang->save();
		$page_act = $article->page_articles[0];
		$page_act->is_online = '1';
		$page_act->save();
		$article->save();
        Session::flash('status_success', $act_lang->title.' başlıklı haber onaylandı.');
        return Redirect::back();
	}

	public function get_unapprove($id)
	{
		$article = CMS\Article::with(array('article_langs', 'page_articles'))->find($id);
		$article->is_approved = '0';
		$act_lang = $article->article_langs()->first();
		$act_lang->is_online = '0';
		$act_lang->is_approve = '0';
		$act_lang->save();
		$page_act = $article->page_articles[0];
		$page_act->is_online = '0';
		$page_act->save();
		$article->save();
        Session::flash('status_success', $act_lang->title.' başlıklı haberin onayı kaldırıldı.');
        return Redirect::back();
	}
}