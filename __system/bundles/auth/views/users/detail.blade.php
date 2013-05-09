@layout('cardea::layout')
@section('title')
{{$user->first_name}} {{$user->last_name}}
@endsection
@section('breadcrumbs')
@parent
<li><a href="{{action('auth::users')}}">Kullanıcılar</a></li>
<li><a href="{{action('auth::users@detail', array($user->id))}}">{{$user->first_name}} {{$user->last_name}}</a></li>
@endsection
@section('jqueries')

	$('#modal-tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
@endsection
@section('maincontent')
<div class="span6">
	<div class="w-box">
		<div class="w-box-header">
			<h4>Kullanıcı Detay : {{$user->first_name}} {{$user->last_name}}</h4>
		</div>
		<div class="w-box-content cnt_a user_profile">
			<div class="row-fluid">
				<div class="span2">
					<div class="img-holder">
						@if(!empty($user->profile_photo))
						<img class="img-avatar" alt="" src="{{URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=80&h=80')}}">
						@else
						<img class="img-avatar" alt="" src="img/avatars/avatar.png">
						@endif
					</div>
				</div>
				<div class="span10">
					<p class="formSep"><small class="muted">Aktif:</small>
						@if($user->activated)
						<span class="label label-success">Evet</span>
						@else
						<span class="label label-important">Hayır</span>
						@endif
					</p>
					<p class="formSep"><small class="muted">Ad Soyad:</small> {{$user->first_name}} {{$user->last_name}}</p>
					<p class="formSep"><small class="muted">E-Posta:</small> {{$user->email}}</p>
					<p class="formSep"><small class="muted">Telefon:</small> {{$user->phone}}</p>
					<p class="formSep"><small class="muted">Ünvan:</small> {{$user->title}}</p>
					<p class="formSep"><small class="muted">Uzmanlık:</small> {{$user->expertise}}</p>
					<p class="formSep"><small class="muted">Kurum:</small> {{$user->company}}</p>
					<p class="formSep"><small class="muted">Şehir:</small> {{$user->city_name()}}</p>
					<p class="formSep"><small class="muted">Cinsiyet:</small> {{$user->gender_text()}}</p>
					<p class="formSep"><small class="muted">Doğum Tarihi:</small> {{$user->birth_date}}</p>
					<p class="formSep"><small class="muted">Üyelik Seviyesi:</small> {{$user->role_name()}}</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="span6">
	<div class="w-box">
		<div class="w-box-header">
			<h4>KULLANICI AYARLARI</h4>
		</div>
		<div class="w-box-content">
			<ul class="nav nav-tabs" id="modal-tabs">
				<li class="active"><a href="#info">Bilgiler</a></li>
				<li><a href="#photo">Profil Fotoğrafı</a></li>
				<li><a href="#password">Şifre Değişikliği</a></li>
			</ul>
			<div class="clearfix"></div>
			<div class="tab-content" style="padding:0;">
				<div class="tab-pane active" id="info">
					{{Form::open('users/update/info/'.$user->id, 'POST', array('class' => 'form-horizontal'))}}
					<div class="control-group">
						<label class="control-label">Ünvan</label>
						<div class="controls">
							{{Form::select('title',array('asd','bsd'),$user->title)}}
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Uzmanlık</label>
						<div class="controls">
							{{Form::text('expertise',$user->expertise)}}
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Şehir</label>
						<div class="controls">
							{{Form::select('city_id',$cities, $user->city_id)}}
						</div>	
					</div>
					<div class="control-group">
						<label class="control-label">Telefon</label>
						<div class="controls">
							{{Form::text('phone',$user->phone,array('data-mask'=>'0(999)999-99-99'))}}
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Kurum Adı</label>
						<div class="controls">
							{{Form::text('company',$user->company)}}
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button type="submit" class="btn btn-primary">Güncelle</button>
						</div>
					</div>
					{{Form::close()}}
				</div>
				<div class="tab-pane" id="photo">
					<center>
						{{Form::open_for_files('users/update/photo/'.$user->id, 'POST', array('class' => 'form-horizontal'))}}
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-preview thumbnail" style="width: 200px; height: 200px;">
								@if(!empty($user->profile_photo))
								<img src="{{URL::to_asset('images/timthumb.php?src=profile_photos/'.$user->profile_photo.'&w=200&h=200')}}">
								@else
								<img src="http://placehold.it/200x200/0eafff/ffffff.png">
								@endif
							</div>
							<div>
								<span class="btn btn-file"><span class="fileupload-new">Fotoğraf Seç</span>
								<span class="fileupload-exists">Başka Seç</span><input type="file" name="profile_photo" /></span>
								<a href="#" class="btn btn-filen fileupload-exists" data-dismiss="fileupload">Kaldır</a>
								<button type="submit" class="btn btn-filen btn-primary fileupload-exists">Kaydet</button>
							</div>
						</div>
						{{Form::close()}}
					</center>		
				</div>
				<div class="tab-pane" id="password">
					{{Form::open('users/update/password/'.$user->id, 'POST', array('class' => 'form-horizontal'))}}
					<div class="control-group">
						<label class="control-label">Eski Şifre</label>
						<div class="controls">
							<input type="password" name="old_password">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Yeni Şifre</label>
						<div class="controls">
							<input type="password" name="new_password1">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Yeni Şifre (Tekrar)</label>
						<div class="controls">
							<input type="password" name="new_password2">
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<button type="submit" class="btn btn-primary">Değiştir</button>
						</div>
					</div>
					{{Form::close()}}
				</div>
			</div>
			<div class="clearfix" style="clear:both;">&nbsp;</div>
		</div>
	</div>
</div>
@endsection