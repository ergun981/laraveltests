<p>{{$user->full_name()}}, {{Config::get('project.name')}} forumlarında yeni bir başlık açtı.</p>
<table border="0">
	<tr>
		<th>
			{{$post->title}}
		</th>
	</tr>
	<tr>
		<td>
			{{$post->content}}
		</td>
	</tr>
</table>
<p>İnceleyip işlem yapmak için <a href="{{url('admin/forum/topics/unapproved_list')}}">buraya</a> tıklayın. Eğer bağlantı çalışmıyorsa aşağıdaki adresi kopyalayıp tarayıcınızın adres çubuğuna yapıştırın.</p>
<p><a href="{{url('admin/forum/topics/unapproved_list')}}">{{url('admin/forum/topics/unapproved_list')}}</a></p>