@layout('admin.layout')
@section('title')
Kullanıcılar
@endsection
@section('breadcrumbs')
@parent
<li><a href="{{url('admin/users')}}">Kullanıcılar</a></li>
@endsection

@section('maincontent')
<div class="w-box w-box-orange">
	<div class="w-box-header">
		<h4>KULLANICILAR</h4>
	</div>
	<div class="w-box-content">
		<table id="dt_basic" class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>id</th>
					<th>Adı Soyadı</th>
					<th>E-Posta</th>
					<th>Ünvan</th>
					<th>Uzmanlık</th>
					<th>Kurum</th>
					<th>Şehir</th>
					<th>Telefon</th>
					<th>Cinsiyet</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@forelse($users as $user)
				<tr>
					<td>{{$user->id}}</td>
					<td>{{$user->first_name}} {{$user->last_name}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->title}}</td>
					<td>{{$user->expertise}}</td>
					<td>{{$user->company}}</td>
					<td>{{$user->city_name()}}</td>
					<td>{{$user->phone}}</td>
					<td>{{$user->gender_text()}}</td>
					<td><a href="{{url('admin/users/detail/'.$user->id)}}"><i class="icon icon-edit"></i></td>
				</tr>
				@empty
				<tr><td colspan="9">Kullanıcı Bulunmuyor</td></tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('jqueries')

$(document).ready(function() {
//* datatables
beoro_datatables.basic();
beoro_datatables.hScroll();
beoro_datatables.colReorder_visibility();
beoro_datatables.table_tools();

$('.dataTables_filter input').each(function() {
$(this).attr("placeholder", "Arama");
})
});

beoro_datatables = {
basic: function() {
if($('#dt_basic').length) {
$('#dt_basic').dataTable({
"sPaginationType": "bootstrap_full",
"iDisplayLength" : 25,
});
}
}
};
@endsection