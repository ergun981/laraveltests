<p>{{$user->full_name()}}, {{Config::get('project.name')}}'da yeni bir olgu paylaştı.</p>
<table border="0">
	<tr>
		<td rowspan="2">
			<img src="{{URL::to_asset('images/timthumb.php?src=questions/'.$olgu->image.'&h=100&w=100')}}" alt="{{$olgu->title}}"/>
		</td>
		<th>
			{{$olgu->title}}
		</th>
	</tr>
	<tr>
		<td>
			{{$olgu->content}}
		</td>
	</tr>
</table>
<p>İnceleyip işlem yapmak için <a href="{{url('admin/olgular/unapproved')}}">buraya</a> tıklayın. Eğer bağlantı çalışmıyorsa aşağıdaki adresi kopyalayıp tarayıcınızın adres çubuğuna yapıştırın.</p>
<p><a href="{{url('admin/olgular/unapproved')}}">{{url('admin/olgular/unapproved')}}</a></p>