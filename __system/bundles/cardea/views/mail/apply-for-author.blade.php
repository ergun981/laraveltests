<p>{{Config::get('project.name')}}'da bir üye yazar olma talebinde bulundu.</p>
<table border="0">
	<tr>
		<td>Ad</td>
		<td>{{$user->first_name}}</td>
	</tr>
	<tr>
		<td>Soyad</td>
		<td>{{$user->last_name}}</td>
	</tr>
	<tr>
		<td>Ünvan</td>
		<td>{{$user->title}}</td>
	</tr>
	<tr>
		<td>Uzmanlık</td>
		<td>{{$user->expertise}}</td>
	</tr>
	<tr>
		<td>Şehir</td>
		<td>{{$user->city_name()}}</td>
	</tr>
	<tr>
		<td>Telefon</td>
		<td>{{$user->phone}}</td>
	</tr>
	<tr>
		<td>Çalışma Yeri</td>
		<td>{{$user->company}}</td>
	</tr>
	<tr>
		<td>E-Posta</td>
		<td>{{$user->email}}</td>
	</tr>
</table>
<p>Kullanıcının profilini görüntülemek için <a href="{{url('users/profile/'.$user->id)}}">buraya</a> tıklayabilirsiniz. Bağlantı çalışmıyorsa aşağıda belirtilen bağlantıyı kopyalayıp tarayıcınızın adres çubuğuna yapıştırabilirsiniz.</p>
<p><a href="{{url('users/profile/'.$user->id)}}">{{url('users/profile/'.$user->id)}}</a></p>