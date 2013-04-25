 <?php 
//Activity nesnesi oluşturur ve nesneye paremetreleri ekleyip kaydeder...
 function save_log($user_id, $action,$content= NULL, $to_user_id = NULL,$to_id = NULL){
 	$activity = new Activity;
 	$action_name = '';
 	$activity->user_id =$user_id;
 	if(is_numeric($action)) {
 		$activity->action_id = $action;
 	}
 	else{ 
 		$act = Action::where('action', '=', $action)->first();
 		if(! is_null($act)) {
 			$activity->action_id = $act->id;
 		}
 	}

 	if(!empty($content)) $activity->content =$content;
 	if(!empty($to_user_id)) $activity->to_user_id =$to_user_id;
 	if(!empty($to_id)) $activity->to_id = $to_id;

 	$activity->ip = get_ip();
 	$activity->save();
 	return $activity;
 }
//verilen activiye göre hesaplama ve kontrolleri yapıp puan ekler
 function action_point(Activity $activity)
 {
 	if($activity->points>0) return false;
 	switch ($activity->action_id) {
		//follow
 		case '9':
 		$check = Activity::where('action_id','=',$activity->action_id)->where('user_id','=',$activity->user_id)->where('to_user_id','=',$activity->to_user_id)->where('points','>','0')->count();
 		if($check>0) return false;
 		$activity->points = Config::get('project.action_points')[$activity->action_id];
 		$activity->save();
 		DB::query('UPDATE users SET points = points + '.$activity->points.' WHERE id = '.$activity->user_id);
 		break;
		//video-watch
 		case '10':
 		$today = date('Y-m-d');
 		$check = Activity::where_action_id_and_user_id_and_to_id($activity->action_id, $activity->user_id, $activity->to_id)->where('points','>','0')->where('created_at','>=',$today)->where('created_at','<',date('Y-m-d H:i:s', strtotime($today . ' + 1 day')))->count();
 		if($check>0) return false;
 		$activity->points = Config::get('project.action_points')[$activity->action_id];
 		$activity->save();
 		DB::query('UPDATE users SET points = points + '.$activity->points.' WHERE id = '.$activity->user_id);
 		break;
		//profile-photo
 		case '23':
 		$check = Activity::where('action_id','=',$activity->action_id)->where('user_id','=',$activity->user_id)->where('points','>','0')->count();
 		if($check>0) return false;
 		$activity->points = Config::get('project.action_points')[$activity->action_id];
 		$activity->save();
 		DB::query('UPDATE users SET points = points + '.$activity->points.' WHERE id = '.$activity->user_id);
 		break;
		//olgu-share (olgu-approve)
 		case '26':
		//forumTitle-approve
 		case '27':
		//olgu-correct-answer
 		case '24':
 		$check = Activity::where('action_id','=',$activity->action_id)->where('to_user_id','=',$activity->to_user_id)->where('content','=',$activity->content)->where('points','>','0')->count();
 		if($check>0) return false;
 		$activity->points = Config::get('project.action_points')[$activity->action_id];
 		$activity->save();
 		DB::query('UPDATE users SET points = points + '.$activity->points.' WHERE id = '.$activity->to_user_id);
 		break;
		//olgu-reply
 		case '13':
		//forumTitle-open
		//case '14':
		//apply-for-author
 		case '19':
		//register
 		case '21':
		//editor-article
 		case '22':
		//olgu-answer-comment
 		case '25':
 		$activity->points = Config::get('project.action_points')[$activity->action_id];
 		$activity->save();
 		DB::query('UPDATE users SET points = points + '.$activity->points.' WHERE id = '.$activity->user_id);
 		break;
 	}
 }


//ip adresini alır..
 function get_ip() {
 	return Request::ip();
 }
//verilen zaman parametresini db zamanına çevirir.
 function sql_timestamp($time = null)
 {
 	if ($time == null)
 	{
 		$time = time();
 	}

 	return date(DB::grammar()->grammar->datetime, $time);
 }

