 <?php 
//Activity nesnesi oluşturur ve nesneye paremetreleri ekleyip kaydeder...
 function save_log($user_id, $action,$content= NULL, $to_user_id = NULL,$to_id = NULL){
 	$activity = new Activity;
 	$action_name = '';
 	$activity->user_id =$user_id;
 	if(is_numeric($action)) {
 		$activity->action_id = $action;
 		$action_name = Action::find($activity->action_id)->action;
 	}
 	else{ 
 		$action_name = $action;
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
 	foreach (Config::get('mail') as $m_action => $mails) {
 		if($action_name == $m_action){
 			$emails = array();
 			if($m_action == 'follow'){
 				$emails = array(User::find($to_user_id)->email => User::find($to_user_id)->full_name());
 			}
 			else{
 				foreach ($mails['to'] as $to) {
 					$emails = array_merge($emails, Config::get('project.other_mails')[$to]);
 				}
 			}
 			if($m_action =='olgu-share'){
 				$data=array(
 					'user'=>User::find($user_id),
 					'olgu'=> QA\Question::find((int)$content)
 					); 
 			}else if($m_action =='forumTitle-open'){
 				$data=array(
 					'user'=>User::find($user_id),
 					'post'=> Forum\Post::find((int)$content)
 					); 
 			}
 			else{ 				
 				$data=array('user'=>User::find($user_id));
 			}
 			Message::to($emails)
 			->from(array('welcome@'.explode('/',explode('://',URL::base())[1])[0] => Config::get('project.name')))
 			->subject($mails['subject'])
 			->body(render('mail.'.$m_action,$data))
 			->html(true)
 			->header('X-MC-Tags',$m_action)
 			->header('X-MC-Track','opens, clicks')
 			->header('X-MC-Autotext','y')
 			->header('X-MC-URLStripQS','true')
 			->send();
 		} 

 	}
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
 //mail içine gönderilecek olan kişi, mail, gibi değişkenleri düzenler...
 //$message mail sablonu, $data değişken veriler, $replacer değişken tanımlarıdır...
 function replace_config($message, $data=array(''), $replacer = array('%var%'))
 {
 	$message = str_replace($replacer, $data , $message);
 	return $message;
 }
 function setAssets()
 {
 	Asset::container('header')->add(Config::get('project.sname'), 'assets/zoneportal/css/'.Config::get('project.sname').'.css');
 	Asset::container('header')->add('font-awesome', 'assets/zoneportal/css/font-awesome.min.css', Config::get('project.sname'));

	//Asset::container('header')->add('jQuery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
 	Asset::container('header')->add('jQuery', 'assets/admin/js/jquery.min.js');

	//footer -> scripts
 	Asset::container('footer')->add('epigra', 'assets/zoneportal/js/epigra.js','jQuery');
 	Asset::container('footer')->add('bootstrap-transition', 'assets/zoneportal/js/bootstrap-transition.js');
 	Asset::container('footer')->add('bootstrap-alert', 'assets/zoneportal/js/bootstrap-alert.js');
 	Asset::container('footer')->add('bootstrap-modal', 'assets/zoneportal/js/bootstrap-modal.js');
 	Asset::container('footer')->add('bootstrap-tab', 'assets/zoneportal/js/bootstrap-tab.js');
 	Asset::container('footer')->add('bootstrap-tooltip', 'assets/zoneportal/js/bootstrap-tooltip.js');
 	Asset::container('footer')->add('bootstrap-popover', 'assets/zoneportal/js/bootstrap-popover.js');
 	Asset::container('footer')->add('bootstrap-button', 'assets/zoneportal/js/bootstrap-button.js');
 	Asset::container('footer')->add('bootstrap-collapse', 'assets/zoneportal/js/bootstrap-collapse.js');
 	Asset::container('footer')->add('bootstrap-carousel', 'assets/zoneportal/js/bootstrap-carousel.js');
 	Asset::container('footer')->add('bootstrap-typeahead', 'assets/zoneportal/js/bootstrap-typeahead.js');
 	Asset::container('footer')->add('counter', 'assets/zoneportal/js/jquery.countdown.min.js');
 	Asset::container('footer')->add('js-validate', 'assets/admin/js/lib/jquery-validation/jquery.validate.min.js','jQuery');
 }

 function setFancyAssets()
 {
 	Asset::container('footer')->add('js-mousewheel-3.0.6.pack','assets/zoneportal/fancy/lib/jquery.mousewheel-3.0.6.pack.js');
 	Asset::container('footer')->add('js-fancybox.pack','assets/zoneportal/fancy/source/jquery.fancybox.pack.js');
 	Asset::container('header')->add('js-fancyboxcss','assets/zoneportal/fancy/source/jquery.fancybox.css');
 }


 function actionBox(Activity $activity, User $user=NULL)
 {
 	if(empty($user)) $user = User::find($activity->user_id);
 	switch ($activity->action_id) {
 		case 9:
 		$return = "";
 		if($activity->content == 'onay') {
 			$return = 
 			'<div class="activity row-fluid well">
 			<div class="span2 ta-center">';
 			if(!empty($user->profile_photo)){
 				$return .= '<img alt="" src="'.URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=60&h=60').'" />';
 			}else{
 				$return .= '<img alt="" src="http://placehold.it/60x60/0eafff/ffffff.png" />';
 			}
 			$return .= '</div>
 			<div class="span10">
 			<p class="sentence">'.$user->full_name().' <a href="'.URL::to('users/profile/'.$activity->to_user_id).'">bir kişiyi</a> takip etti.</p>
 			<p class="title">'.User::find($activity->to_user_id)->full_name().'</p>
 			<div class="actions"><small><a href="'.URL::to('users/profile/'.$activity->to_user_id).'"><i class=" icon-align-justify"></i>Profili Gör</a></small></div>
 			</div>
 			</div>';
 		}
 		break;
 		case 26:
 		$user = User::find($activity->to_user_id);
 		$return = 
 		'<div class="activity row-fluid well">
 		<div class="span2 ta-center">';
 		if(!empty($user->profile_photo)){
 			$return .= '<img alt="" src="'.URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=60&h=60').'" />';
 		}else{
 			$return .= '<img alt="" src="http://placehold.it/60x60/0eafff/ffffff.png" />';
 		}
 		$return .= '</div>
 		<div class="span8">
 		<p class="sentence">'.$user->full_name().' <a href="'.URL::to('olgular/detail/'.$activity->content).'">bir olgu</a> paylaştı.</p>
 		<p class="title">'.QA\Question::find($activity->content)->title.'</p>
 		<div class="actions"><small><a href="'.URL::to('olgular/detail/'.$activity->content).'"><i class=" icon-align-justify"></i>Oku</a></small></div>
 		</div>
 		<div class="span2 ta-center">
 		<a href="'.URL::to('olgular/detail/'.$activity->content).'"><img alt="" src="'.URL::to_asset('images/timthumb.php?src=questions/'.QA\Question::find($activity->content)->image.'&w=60&h=60').'" /></a>
 		</div>
 		</div>';
 		break;
 		case 13:
 		$return = 
 		'<div class="activity row-fluid well">
 		<div class="span2 ta-center">';
 		if(!empty($user->profile_photo)){
 			$return .= '<img alt="" src="'.URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=60&h=60').'" />';
 		}else{
 			$return .= '<img alt="" src="http://placehold.it/60x60/0eafff/ffffff.png" />';
 		}
 		$return .= '</div>
 		<div class="span8">
 		<p class="sentence">'.$user->full_name().' <a href="'.URL::to('olgular/detail/'.$activity->to_id).'">bir olguya</a> cevap verdi.</p>
 		<p class="title">'.QA\Question::find($activity->to_id)->title.'</p>
 		<div class="actions"><small><a href="'.URL::to('olgular/detail/'.$activity->to_id).'"><i class=" icon-align-justify"></i>Oku</a></small></div>
 		</div>
 		<div class="span2 ta-center">
 		<a href="'.URL::to('olgular/detail/'.$activity->to_id).'"><img alt="" src="'.URL::to_asset('images/timthumb.php?src=questions/'.QA\Question::find($activity->to_id)->image.'&w=60&h=60').'" /></a>
 		</div>
 		</div>';
 		break;
 		case 18:
 		$return = 
 		'<div class="activity row-fluid well">
 		<div class="span2 ta-center">';
 		if(!empty($user->profile_photo)){
 			$return .= '<img alt="" src="'.URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=60&h=60').'" />';
 		}else{
 			$return .= '<img alt="" src="http://placehold.it/60x60/0eafff/ffffff.png" />';
 		}
 		$return .= '</div>
 		<div class="span8">
 		<p class="sentence">'.$user->full_name().' <a href="'.URL::to('olgular/detail/'.$activity->content).'">bir olguyu</a> favorilerine ekledi.</p>
 		<p class="title">'.QA\Question::find($activity->content)->title.'</p>
 		<div class="actions"><small><a href="'.URL::to('olgular/detail/'.$activity->content).'"><i class=" icon-align-justify"></i>Oku</a></small></div>
 		</div>
 		<div class="span2 ta-center">
 		<a href="'.URL::to('olgular/detail/'.$activity->content).'"><img alt="" src="'.URL::to_asset('images/timthumb.php?src=questions/'.QA\Question::find($activity->content)->image.'&w=60&h=60').'" /></a>
 		</div>
 		</div>';
 		break;
 		case 27:
 		$user = User::find($activity->to_user_id);
 		$return = 
 		'<div class="activity row-fluid well">
 		<div class="span2 ta-center">';
 		if(!empty($user->profile_photo)){
 			$return .= '<img alt="" src="'.URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=60&h=60').'" />';
 		}else{
 			$return .= '<img alt="" src="http://placehold.it/60x60/0eafff/ffffff.png" />';
 		}
 		$return .= '</div>
 		<div class="span10">
 		<p class="sentence">'.$user->full_name().' forumda <a href="'.URL::to('forum/topic/'.$activity->content).'">yeni bir konu</a> oluşturdu.</p>
 		<p class="title">'.Forum\Topic::find($activity->content)->subject.'</p>
 		<div class="actions"><small><a href="'.URL::to('forum/topic/'.$activity->content).'"><i class=" icon-align-justify"></i>Oku</a></small></div>
 		</div>
 		</div>';
 		break;
 		case 15:
 		$return = 
 		'<div class="activity row-fluid well">
 		<div class="span2 ta-center">';
 		if(!empty($user->profile_photo)){
 			$return .= '<img alt="" src="'.URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=60&h=60').'" />';
 		}else{
 			$return .= '<img alt="" src="http://placehold.it/60x60/0eafff/ffffff.png" />';
 		}
 		$return .= '</div>
 		<div class="span10">
 		<p class="sentence">'.$user->full_name().' forumda <a href="'.URL::to('forum/topic/'.$activity->to_id.'#'.$activity->content).'">bir konuya</a> cevap verdi.</p>
 		<p class="title">'.Forum\Topic::find($activity->to_id)->subject.'</p>
 		<div class="actions"><small><a href="'.URL::to('forum/topic/'.$activity->to_id.'#'.$activity->content).'"><i class=" icon-align-justify"></i>Oku</a></small></div>
 		</div>
 		</div>';
 		break;
 		case 20:
 		$return = 
 		'<div class="activity row-fluid well">
 		<div class="span2 ta-center">';
 		if(!empty($user->profile_photo)){
 			$return .= '<img alt="" src="'.URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=60&h=60').'" />';
 		}else{
 			$return .= '<img alt="" src="http://placehold.it/60x60/0eafff/ffffff.png" />';
 		}
 		$return .= '</div>
 		<div class="span10">
 		<p class="sentence">'.$user->full_name().' bir durum paylaştı.</p>
 		<p class="title">'.$activity->content.'</p>
 		<div class="actions"><small><a href="'.URL::to('users/profile/'.$activity->user_id).'"><i class=" icon-align-justify"></i>Profili Gör</a></small></div>
 		</div>
 		</div>';
 		break;
 		default:
 		return '';
 		break;
 	}
 	return $return;
 }
