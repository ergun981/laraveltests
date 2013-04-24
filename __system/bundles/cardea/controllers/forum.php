<?php

class Forum_Controller Extends Base_Controller
{
	public $restful = true;

	public function __construct(){
		parent::__construct();
		$this->filter('before','auth');
		$forums = Forum\Category::all();

		$forums_tree = Epigra::buildTree( $forums );
		$forums_dd = array_merge(array(0 => '---'),Forum\Category::where('parent_id','=',0)->lists('title', 'id'));

		
		View::share('forums_tree', $forums_tree);
		View::share('forums_dd', $forums_dd);
	}

	public function get_index()
	{
		$categories = Forum\Category::where('parent_id','=','0')->order_by('order','asc')->get();
		$children = array();
		foreach ($categories as $category) {
			$children_categories = Forum\Category::where('parent_id','=',$category->id)->order_by('order','asc')->get();
			if(!empty($children_categories)) $children[$category->id] = $children_categories;
		}
		$data = array('categories' => $categories, 'children' => $children);
		return View::make('zoneportal.forum.forum',$data);
	}

	public function get_category($id, $newTopic = false)
	{
		//forum iç sayfası, alt forumlar ve konular listelenir
		if($newTopic=="new") return $this->newTopicForm($id);
		$category = Forum\Category::find($id);
		$topics = Forum\Topic::where('category_id','=',$id)->where('is_approved','=','1')->order_by('last_post_time','desc')->paginate(10);
		$data = array('category' => $category, 'topics' => $topics);
		return View::make('zoneportal.forum.category', $data);
	}

	private function newTopicForm($id)
	{
		Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
		//forum sayfasına /new talebi ile gelindiğinde çalıştırılan yeni konu formu
		$title = Forum\Category::find($id)->title;
		if(is_null($title)) return Redirect::to('forum');
		$data = array(
			'category_id' 		=> $id,
			'category_title' 	=> $title
			);
		return View::make('zoneportal.forum.newtopicform',$data);
	}

	public function post_category($id)
	{
		//yeni konu için gönderilen formun yakalanıp ekleme işleminin yapıldığı alan
		$posted = Input::get();
		
		$v = Forum\Topic::validate($posted);
		if($v) return Redirect::back()->with_errors($v)->with_input();
		
		//$category = Forum\Category::find($id);
		$newTopic = array(
			'category_id' 	=> $id,
			'subject' 		=> $posted['subject'],
			'poster_id' 	=> Auth::user()->id,
			);
		$topic = Forum\Topic::create($newTopic);
		
		$newPost = array(
			'topic_id' 		=> $topic->id,
			'category_id' 	=> $id,
			'poster_id' 	=> Auth::user()->id,
			'poster_ip' 	=> get_ip(),
			'subject' 		=> $topic->subject,
			'text' 			=> $posted['text'],
			);
		$post = Forum\Post::create($newPost);
		
		$topic->last_post_id 	= $post->id;
		$topic->last_poster_id 	= $post->poster_id;
		$topic->last_post_time 	= $post->created_at;
		$topic->save();
		
		// $category->topics 				= $category->topics+1;
		// $category->posts 				= $category->posts+1;
		// $category->last_post_id 		= $post->id;
		// $category->last_poster_id 		= $post->poster_id;
		// $category->last_topic_subject 	= $topic->subject;
		// $category->last_post_time 		= $post->created_at;
		// $category->save();
		save_log(Auth::user()->id, 'forumTitle-open', $topic->id, NULL, NULL);
		Cache::forever('c_forum',Forum\Topic::where_is_approved('0')->count());
		return Redirect::to('forum/topic/'.$topic->id);
	}

	public function get_topic($id)
	{
		Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
		Asset::container('footer')->add('js-form', 'assets/admin/js/lib/jquery-validation/lib/jquery.form.js','jQuery');
		Asset::container('footer')->add('js-validate', 'assets/admin/js/lib/jquery-validation/jquery.validate.min.js','jQuery');
		//konu
		$topic = Forum\Topic::with(array('poster','posts'=>function($q){$q->order_by('created_at','asc');},'posts.poster'))->where_id_and_is_approved($id, '1')->first();
		if(is_null($topic)) return Redirect::to('forum');
		$topic->views = $topic->views+1;
		$topic->save();
		$posts = $topic->posts()->order_by('created_at','asc')->paginate(10);
		$data = array(
			'topic' => $topic,
			'posts' => $posts
			);
		return View::make('zoneportal.forum.topic', $data);
	}
	public function post_topic($id)
	{
		//topic üzerinden gelen yeni mesajın yakalanarak eklendiği alan
		$posted = Input::get();
		$v = Forum\Post::validate($posted);

		if($v) return Redirect::back()->with_errors($v)->with_input();

		$topic = Forum\Topic::find($id);
		$category = Forum\Category::find($topic->category_id);
		
		$newPost = array(
			'topic_id' 		=> $id,
			'category_id' 	=> $category->id,
			'poster_id' 	=> Auth::user()->id,
			'poster_ip' 	=> get_ip(),
			'subject' 		=> 'RE: '.$topic->subject,
			'text' 			=> $posted['text']
			);
		$post = Forum\Post::create($newPost);

		$topic->last_post_id 	= $post->id;
		$topic->last_poster_id 	= $post->poster_id;
		$topic->last_post_time 	= $post->created_at;
		$topic->replies 		= $topic->replies+1;
		$topic->save();

		$category->posts 				= $category->posts+1;
		$category->last_post_id 		= $post->id;
		$category->last_poster_id 		= $post->poster_id;
		$category->last_topic_subject 	= $topic->subject;
		$category->last_post_time 		= $post->created_at;
		$category->save();
		save_log(Auth::user()->id, 'forumTitle-reply', $post->id , NULL, $topic->id);
		return Redirect::to('forum/topic/'.$topic->id);
	}


	public function post_new()
	{
		
	}
	public function post_edit_post($id)
	{
		$post = Forum\Post::find($id);
		if($post) {
			$post->text = Input::get('text');
			$post->save();
			return $post->text;
		}
		return false;
	}

	public function get_delete_post($id)
	{
		$post = Forum\Post::find($id);
		if($post) {
			return $post->delete_post();
		}
		return false;
	}
	public function get_delete_topic($id)
	{
		$topic = Forum\Topic::find($id);
		if($topic) {
			$topic->delete_topic();
			return true;
		}
		return false;
	}

}

?>