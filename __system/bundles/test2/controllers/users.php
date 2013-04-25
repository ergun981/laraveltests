<?php
class Auth_Users_Controller extends Controller {
	public $restful = true;

	public function __construct()
	{
		$this->filter('before', 'auth')->only(array('index', 'profile', 'follow', 'followers', 'followings', 'scoreboard', 'scorelist'));
	}

	public function get_index() {

		return "index";
	}

	public function get_profile($id = NULL) {
		setAssets();
		Asset::container('footer')->add('jasny-input','assets/zoneportal/js/bootstrap-inputmask.js','jQuery');
		Asset::container('footer')->add('jasny-file','assets/zoneportal/js/bootstrap-fileupload.js','jQuery');

		if(is_null($id)) return Redirect::to('users/profile/'.Auth::user()->id);
		$user = User::find($id);
		$last_forum_posts = Forum\Post::where('poster_id','=',$id)->order_by('id','desc')->take('5')->get();
		$last_shared_questions = QA\Question::where_user_id_and_is_approved($id, '1')->order_by('id','desc')->take('5')->get();
		$cities = array('-');
		$get_cities = City::all();
		foreach ($get_cities as $city) {
			$cities[$city->id] = $city->name;
		}
		$act = Activity::where_user_id_and_action_id_and_to_user_id(Auth::user()->id, 9, $id)->first();
		$user_follow_check = '1';
		if($act) {
			if($act->content == 'istek') $user_follow_check = '2';
			else if($act->content == 'red') $user_follow_check = '-1';
			else {
				$check = Auth::user()->followings()->where('users.id','=',$id)->count();
				if($check) $user_follow_check = '0';
				else $user_follow_check = '1';
			}
		}
		$user_activities = Activity::where_in('action_id',Config::get('project.follow_actions')[0])->where('user_id','=',$user->id)->or_where_in('action_id',Config::get('project.follow_actions')[1])->where('to_user_id','=',$user->id)->order_by('id','desc')->paginate(10);
		$data = array('user'=>$user,'posts' => $last_forum_posts,'questions'=>$last_shared_questions,'cities'=>$cities, 'following' => $user_follow_check,'activities'=>$user_activities);
		return View::make('zoneportal.user.profile', $data);
	}

	public function post_profile()
	{
		save_log(Auth::user()->id,'status',Input::get('status'));
		Session::flash('status_success', 'Durumunuz güncellendi.');
		return Redirect::to('dashboard');
	}
	public function post_contact_mail()
	{
		if((Input::get('subject')=="") || Input::get('content')=="") return Redirect::back();
		Message::to(Config::get('project.adminmail'))
		->from(array(Auth::user()->email=>Auth::user()->full_name()))
		->subject(Config::get('project.name')." İletişim Formu : ".Input::get('subject'))
		->body("<p>".Auth::user()->full_name()." iletişim formu üzerinden bir mesaj gönderdi; </p>".Input::get('content'))
		->html(true)
		->header('X-MC-Tags','contact_form')
		->header('X-MC-Track','opens, clicks')
		->header('X-MC-Autotext','y')
		->header('X-MC-URLStripQS','true')
		->send();
		Session::flash('status_success', 'Mesajınız  gönderildi.');
		return Redirect::back();
	}
	public function get_login()
	{
		if ( Auth::check() ) return Redirect::to('dashboard');
		//dd(User\Role::all());
		Asset::container('header')->add(Config::get('project.sname'), 'assets/zoneportal/css/'.Config::get('project.sname').'.css');
		//Asset::container('header')->add('jQuery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
		Asset::container('header')->add('jQuery', 'assets/admin/js/jquery.min.js');
		//footer -> scripts
		Asset::container('footer')->add('epigra', 'assets/zoneportal/js/epigra.js','jQuery');
		Asset::container('footer')->add('js-form', 'assets/admin/js/lib/jquery-validation/lib/jquery.form.js','jQuery');
		Asset::container('footer')->add('js-validate', 'assets/admin/js/lib/jquery-validation/jquery.validate.min.js','jQuery');
		Asset::container('footer')->add('js-bootstrap.min', 'assets/admin/bootstrap/js/bootstrap.min.js','jQuery');
		Asset::container('footer')->add('jasny-input','assets/zoneportal/js/bootstrap-inputmask.js','jQuery');

		$cities = array('-');
		$get_cities = City::all();
		foreach ($get_cities as $city) {
			$cities[$city->id] = $city->name;
		}

		$data = array('cities' => $cities);
		return view('zoneportal.welcome', $data);
	}


	public function get_register()
	{
		return View::make('users.register');
	}

	/**
	 * Create a new account for the user
	 *
	 * @return Redirection
	 */
	public function post_register()
	{
		// Precaution - just in case user has other account
		if ( Auth::check() ) Auth::logout();
		$form = Input::get();
		$v = User::validate($form);
		if(!$v){
			$temp_password = Str::random(8);
			$user = new User;
			$user->first_name = $form['first_name'];
			$user->last_name = $form['last_name'];
			$user->email = $form['email'];
			$user->title = $form['title'];
			$user->expertise = $form['expertise'];
			$user->city_id = $form['city_id'];
			$user->phone = $form['phone'];
			$user->company = $form['company'];
			$user->password = $temp_password;
			$user->activation_code = User::generate_activation();
			$user->role_id = 7;
			$user->save();
			
			if(!empty($user->id)) {
				// If successful, send confirmation/welcome email
				$aktivasyonLink = URL::base().'/users/activate/'.$user->activation_code.'/'.$user->id;
				
				Message::to($user->email)
				->from(array('welcome@'.explode('/',explode('://',URL::base())[1])[0] => Config::get('project.name')))
				->subject(Config::get('project.name')."'a Hoşgeldiniz!")
				->body('<p>Sayın '.$user->full_name().';</p><p>'.Config::get('project.name').'\'a Hoşgeldiniz!</p><p>Lütfen <a href="'.$aktivasyonLink.'">buraya</a> tıklayarak üyeliğinizi aktif edin ve aşağıda belirtilen şifrenizi kullanarak giriş yapın. Aktivasyon linkine tıklayamıyorsanız aşağıda belirtilen aktivasyon adresini kopyalayarak tarayıcınızın adres çubuğuna yapıştırın.</p><p>Aktivasyon Adresi : '.$aktivasyonLink.'</p><p>Şifreniz : '.$temp_password.'</p>')
				->html(true)
				->header('X-MC-Tags','user_register')
				->header('X-MC-Track','opens, clicks')
				->header('X-MC-Autotext','y')
				->header('X-MC-URLStripQS','true')
				->send();
				
				return true;
			}
		}
		else{
			return false;
		}

		return fasle;	
	}

	public function post_update($type, $id)
	{
		if(Auth::user()->id!=$id and Auth::user()->level() < 1000) return Redirect::back();
		$user = User::find($id);
		if(empty($user)) return Redirect::back();
		$form = Input::get();
		switch ($type) {
			case 'info':
			$user->title = $form['title'];
			$user->expertise = $form['expertise'];
			$user->city_id = $form['city_id'];
			$user->phone = $form['phone'];
			$user->company = $form['company'];
			$user->save();
			Session::flash('status_success','Profil bilgileri güncellendi.');
			return Redirect::to('users/profile/'.$user->id);
			break;

			case 'photo':
			if(!empty($user->profile_photo))
			{
				File::delete(path('public').'/images/profile_photos/'.$user->profile_photo);
			}
			$image_name = $user->id.'_'.Str::random(32);
			$image_ext = File::extension(Input::file('profile_photo.name'));
			$profile_photo = $image_name.'.'.$image_ext;
			$image = Input::upload('profile_photo', path('public').'/images/profile_photos', $profile_photo);
			$user->profile_photo = $profile_photo;
			$user->save();
			action_point(save_log($user->id,'profile-photo'));
			Session::flash('status_success','Profil fotoğrafı değiştirildi.');

			return Redirect::to('users/profile/'.$user->id);
			break;

			case 'password':
			if(Hash::check($form['old_password'], $user->password)){
				if($form['new_password1'] == $form['new_password2'])
				{
					$user->set_password($form['new_password1']);
					Session::flash('status_success', 'Şifreniz değiştirildi. Lütfen yeni şifrenizle giriş yapın.');
					Auth::logout();
					return Redirect::to('/');
				}
				else{
					Session::flash('status_error', 'Girdiğiniz şifreler uyuşmuyor.');
				}
			}
			else{
				Session::flash('status_error', 'Mevcut şifrenizi yanlış girdiniz.');
			}
			return Redirect::to('users/profile/'.$user->id);
			break;
			
			default:
				# öyle şey mi olur canım, erör ver
			return Redirect::back();
			break;
		}
	}

	public function get_follow($id)
	{
		$check = Auth::user()->followings()->where('users.id','=',$id)->count();
		if($check>0)
		{
			Auth::user()->followings()->where('users.id','=',$id)->delete();
			Activity::where_user_id_and_action_id_and_to_user_id(Auth::user()->id, 9, $id)->delete();
			return '<a href="javascript:;" style="width: 80px;text-align: center;background-color:#003882;color:#fff;margin:3 auto;display:block;"><i class="icon-plus"></i> Takip Et</a>';
		}
		else
		{
			//content:istek => takip isteği, content:onay => takip onay, content:red => takip red(engellendi)
			//Auth::user()->followings()->attach($id);
			save_log(Auth::user()->id, 'follow', 'istek', $id, NULL);
			return '<span style="width: 80px;text-align: center;background-color:#B19918;color:#212121;margin:3 auto;display:block;"><i class="icon-spinner icon-spin"></i> Bekleniyor</a>';
		}
	}
	public function get_req_follow($id, $req=NULL){
		if($req=='no-submit'){
			$act = Activity::where_user_id_and_action_id_and_to_user_id_and_content($id, 9, Auth::user()->id, 'istek')->first();
			if($act){
				$act->delete();				
				return 2;
			}
		}
		elseif($req=='ex'){
			$act = Activity::where_user_id_and_action_id_and_to_user_id_and_content($id, 9, Auth::user()->id, 'onay')->first();
			if($act){
				$act->delete();	
			}
			Auth::user()->followers()->where('users.id','=',$id)->delete();	
			return 2;
		}
		elseif($req=='unfollow'){
			$act = Activity::where_user_id_and_action_id_and_to_user_id_and_content(Auth::user()->id, 9, $id, 'onay')->first();
			if($act){
				$act->delete();	
			}
			Auth::user()->followings()->where('users.id','=',$id)->delete();
			return 2;
		}
		elseif($req=='block'){
			$act = Activity::where_user_id_and_action_id_and_to_user_id($id, 9, Auth::user()->id)->where_in('content', array('istek', 'onay', 'red'))->first();
			if($act){
				$act->content = 'red';
				$act->save();
			}
			if( Auth::user()->followings()->where('users.id','=',$id)->first())					
				Auth::user()->followings()->where('users.id','=',$id)->delete();				
			return 2; //remove-list
		}
		else {
			$act = Activity::where_user_id_and_action_id_and_to_user_id_and_content($id, 9, Auth::user()->id, 'istek')->first();
			if($act){
			// 	$act->content = 'onay';
			// 	$act->points = Config::get('project.action_point')[9];
			// 	$user = $act->user;
			// 	$user->points += Config::get('project.action_point')[9];
			// 	$user->save();
			// //puan verme bu eylem için burda tanımlandı.... ayrıca takip edilen kullanıcı içinde puan verilmesi durumu soz konusu olabilir....
			// 	$act->save();	
				$act->content = 'onay';
				$act->save();
				action_point($act);		
				Auth::user()->followers()->attach($id);	
			return 1; //remove-action
		}

	}
	return false;

}
public function get_followings() {
	setAssets();
	$users=Auth::user()->followings()->get();
	$data = array('users' => $users, 'to_me' => true);
	return view('zoneportal.user.followlist', $data);
}
public function get_followers() {
	setAssets();
	$users=Activity::with(array('user'))->where_to_user_id_and_action_id(Auth::user()->id, 9)->where_in('content', array('istek', 'onay'))->order_by('content', 'desc')->get();
	$data = array('users' => $users, 'to_me' => false);
	return view('zoneportal.user.followlist', $data);
}

	// public function get_score_doc(){
	// 	$user = Auth::user();

	// 	Activity::where('points', '>', 0)
	// 				->where()
	// 				->where('user_id', '=', $user->id)
	// 				->get();
	// }

public function post_invite_friend(){
	$data = array('email'=>Input::get('email'));
	$rules = array(
		'email'     => 'required|between:7,64|email|unique:users',
		);

	$v= Validator::make($data, $rules);

	if(! $v->valid()){			
		Session::flash('status_error', 'E-posta davet için geçersiz.');
		return Redirect::back();
	}
	else if(Activity::where_action_id_and_content(17, $data['email'])->count() < 1){
		Message::to($data['email'])
		->from(array('welcome@'.explode('/',explode('://',URL::base())[1])[0] => Config::get('project.name')))
		->subject(Config::get('project.name').' Sistemine Davet Edildiniz!')
		->body('<p>Sayın '.$data['email'].';</p><p>'.Config::get('project.name').' Sistemine davet edildiniz!</p><p>'.Auth::user()->full_name(). 'adlı kullanıcımız size bir davet gönderdi.</p><p> <a href="'.URL::base().'"> sitemizi </a> ziyaret ederek kaydınızı gercekleştirebilirsiniz.</p><p> İyi zamanlar dileriz. </p>' )
		->html(true)
		->header('X-MC-Tags','invite_friend')
		->header('X-MC-Track','opens, clicks')
		->header('X-MC-Autotext','y')
		->header('X-MC-URLStripQS','true')
		->send();
		save_log(Auth::user()->id, "friend-invite", $data['email']);

		Session::flash('status_success', 'Arkadaşınıza davet iletisi gönderildi.');
	}
	else 
		Session::flash('status_error', 'E-posta, daha önce istek gönderilmiş bir kişiye ait.');
	return Redirect::back();
}


	/**
	 * Reset the user's password
	 *
	 * @return Redirection
	 */
	public function post_reset()
	{
		$reset = User::reset_password(Input::get('email'));

		if ( !$reset ) {
			Session::flash('status_error', 'Üzgünüz, bu mail adresiyle kayıtlı bir üye bulamadık.');
			return Redirect::back();
		} else {
			Message::to(Input::get('email'))
			->from(array('no-reply@'.explode('/',explode('://',URL::base())[1])[0] => Config::get('project.name')))
			->subject(Config::get('project.name').' Şifre Sıfırlama')
			->body('<p>'.Config::get('project.name').' şifrenizi sıfırladınız!</p><p>'.URL::base(). ' adresine girerek aşağıda belirtilen yeni şifreniz ile giriş yapabilirsiniz..</p><p>Giriş yaptıktan sonra profil sayfanızdaki Profili Düzenle bağlantısını tıklayarak açılan pencereden şifrenizi değiştirebilirsiniz.</p><p>Yeni Şifreniz : <b>'.$reset.'</b></p><p> <a href="'.URL::base().'"> Buraya tıklayarak </a> giriş ekranına gidebilirsiniz. </p>' )
			->html(true)
			->header('X-MC-Tags','reset_password')
			->header('X-MC-Track','opens, clicks')
			->header('X-MC-Autotext','y')
			->header('X-MC-URLStripQS','true')
			->send();
			Session::flash('status_success', 'Yeni şifre mail adresinize gönderildi.');
			return Redirect::to('login');
		}
	}


	public function get_activate($activation, $id)
	{

		if ( $activation && $id ) {
			// try to get the record from the db
			$user = User::where_activation_code_and_id($activation, $id)->first();

			// if found, set activated to true
			if ( $user ) {
				action_point(save_log($user->id,'register'));
				$user->activation_code = NULL;
				$user->activated = 1;
				$user->role_id = 4;
				$user->save();

				$act_invite = Activity::where_action_id_and_content_and_points(17, $user->email, 0)->first();
				if($act_invite) {
					$act_invite->points = Config::get('project.action_points')[$act_invite->action_id];
					$act_invite->save();
					$user = $act_invite->user()->first();
					$user->points += Config::get('project.action_points')[$act_invite->action_id];
					$user->save();
				}
				Session::flash('status_success', 'Hesabınız aktifleştirildi!');
				// if ( Auth::login($user) ) {
				// 	save_log(Auth::user()->id, 'login');
				// 	return Redirect::to('dashboard');
				// }
				return Redirect::to('/');
			} else {
				Session::flash('status_error', 'Hesap aktifleştirme başarısız, lütfen tekrar deneyin.');
				return Redirect::to('/');
			}
		} 
	}

	public function get_scoreboard($id = NULL){
		setAssets();
		if($id == NULL) $user =Auth::user();
		else $user = User::find($id);
		if($user){//@todo:admin kontrolü gelecek...ya da activityler için action point ilşikilendirmesi yapılacak...
			$activities = $user->get_activity_user()->group_by('action_id')->where_in('action_id', array_keys(Config::get('project.action_points')))->where('points', '>', 0)->get(array('id', 'action_id', 'user_id', DB::raw('sum(points) as points')));
			$activities_to = $user->get_activity_to_user()->group_by('action_id')->where_in('action_id', Config::get('project.follow_actions')[1])->where('points', '>', 0)->get(array('id', 'action_id', 'user_id', DB::raw('sum(points) as points')));
			$p_activities = array_merge($activities, $activities_to);
			$actions = array();
			foreach ($p_activities as $activity) {
				$is_exist = false;
				foreach ($actions as $action) {
					if(array_key_exists($activity->action_title(), $action)) {
						$is_exist = true;
						$action[$activity->action_title()] += $activity->points;
					}
				}
				if(! $is_exist)
					array_push($actions, array(Config::get('project.action_title')[$activity->action_id]=> $activity->points));
			}
			return view('zoneportal.user.scoreboard', array('actions' => $actions));
		}
	}
	// @ tobe contu....neeeee... sorgu düzeltilecek....
	public function get_scorelist(){
		setAssets();
		$users = User::where('points','>','0')->or_where('id','=',Auth::user()->id)->order_by('points', 'desc')->get(array('points','first_name','last_name','id'));
		$point_list = array();
		foreach ($users as $user) {
			array_push(
				$point_list,
				array(
					'user_id'  => $user->id,
					'full_name'=> $user->full_name(),
					'point'    => $user->points
					)
				);
		}
		return view('zoneportal.scoreboard', array('point_list'=> $point_list));
	}
}