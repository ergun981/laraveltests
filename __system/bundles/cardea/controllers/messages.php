<?php

class Messages_Controller extends Base_Controller
{
	public $restful = true;

	public function __construct()
	{
		parent::__construct();
		$this->filter('before','auth');
		setAssets();
	}

	public function get_index()
	{
		$per_page = 5;
		$cur_page = 1;
		if(Input::get('page')) $cur_page = Input::get('page');
		$start_from = (int)($cur_page*$per_page) - $per_page;
		$total = DB::query('SELECT COUNT(id) FROM messages where to_user_id='.Auth::user()->id.' group by user_id');
		$messages = DB::query('SELECT * FROM (SELECT * FROM messages where to_user_id='.Auth::user()->id.' ORDER BY created_at DESC) as messages group by user_id ORDER BY created_at DESC LIMIT '.$start_from.','.$per_page);
		$messages = Paginator::make($messages, count($total), $per_page);
		$data = array(
			'messages' => $messages,
			'sent' => false,
			);
		return View::make('zoneportal.message.index', $data);
	}

	public function get_sent()
	{
		$per_page = 5;
		$cur_page = 1;
		if(Input::get('page')) $cur_page = Input::get('page');
		$start_from = (int)($cur_page*$per_page) - $per_page;
		$total = DB::query('SELECT COUNT(id) FROM messages where user_id='.Auth::user()->id.' group by to_user_id');
		$messages = DB::query('SELECT * FROM (SELECT * FROM messages where user_id='.Auth::user()->id.' ORDER BY created_at DESC) as messages group by to_user_id ORDER BY created_at DESC LIMIT '.$start_from.','.$per_page);
		$messages = Paginator::make($messages, count($total), $per_page);
		$data = array(
			'messages' => $messages,
			'sent' => true,
			);
		return View::make('zoneportal.message.index', $data);
	}

	public function get_conversation($user_id)
	{
		$conversation_user = User::find($user_id);
		if(empty($conversation_user)) return Redirect::to('messages');
		Asset::container('footer')->add('js-ckeditor','assets/zoneportal/js/lib/ckeditor/ckeditor.js');
		$messages = PrivateMessage::where_in('user_id',array(Auth::user()->id,$user_id))->where_in('to_user_id',array(Auth::user()->id,$user_id))->order_by('created_at','desc')->paginate(5);
		//$messages = array_reverse($messages);
		//bu konuşma içindeki gelen tüm mesajları okundu olarak işaretleyelim
		DB::query('UPDATE messages SET is_read = 1 WHERE user_id = '.$user_id.' AND to_user_id = '.Auth::user()->id);
		$data = array(
			'messages' => $messages,
			'conversation_user' => $conversation_user,
			);
		return View::make('zoneportal.message.conversation', $data);
	}

	public function post_conversation($user_id)
	{
		$message = new PrivateMessage;
		$message->user_id = Auth::user()->id;
		$message->to_user_id = $user_id;
		$message->text = Input::get('message');
		$message->save();
		Session::flash('status_success','Mesajınız Gönderildi');
		return Redirect::to('messages/conversation/'.$user_id);
	}
}

?>