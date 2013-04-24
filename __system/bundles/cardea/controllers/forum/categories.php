<?php

class Epigra_Admin_Forum_Categories_Controller Extends AdminBase_Controller
{
	public $restful = true;

	public function __construct(){
		parent::__construct();

		$forums = Forum\Category::all();

		$forums_tree = Epigra::buildTree( $forums );
		$forums_dd = array(0 => '---') + Forum\Category::where('parent_id','=',0)->lists('title', 'id');

		
		View::share('forums_tree', $forums_tree);
		View::share('forums_dd', $forums_dd);
	}

	public function get_index()
	{
		
		return View::make('admin.forum.categories');
	}

	public function get_edit($id)
	{
		$data['c'] = Forum\Category::find($id);
		return View::make('admin.forum.edit', $data);
	}

	public function post_new()
	{
		$form = Input::all();

		$forum = new Forum\Category;

        $errors = $forum->validate($form);

        if (!$errors) {
        	

        	$forum->parent_id = $form['parent_id'];
        	$forum->title = $form['title'];
        	$forum->description = $form['description'];
        	$forum->order = 1;
        	$forum->status = 1;

        	$forum->save();
        	Session::flash('status_success', 'Kategori eklendi.');

        	return Redirect::to('admin/forum/categories');
        } else {
        	return Redirect::back()
        	->with_errors($errors)
        	->with_input();
        }

		return View::make('admin.forum.edit', $data);
	}

	public function post_edit()
	{
		$form = Input::all();
		$forum = Forum\Category::find($form['category_id']);
		if(empty($forum)) return Redirect::back();
		$forum->parent_id = $form['parent_id'];
		$forum->title = $form['title'];
        $forum->description = $form['description'];

        $forum->save();
        Session::flash('status_success', 'Kategori başarıyla güncellendi.');

        return Redirect::to('admin/forum/categories');
	}

	public function get_delete($id)
	{
		$data = array('id' => $id);
		return View::make('admin.forum.delete', $data);
	}

	public function post_delete()
	{
		$posted = Input::all();
		$category = Forum\Category::find($posted['id']);
		$category->delete_all();

		Session::flash('status_success', 'Kategori başarıyla silindi.');

        return Redirect::to('admin/forum/categories');
	}

}

?>