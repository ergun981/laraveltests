@layout('admin.layout')
@section('breadcrumbs')
@parent
<li><span> Forum Başlıkları</span></li>
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
"iDisplayLength" : 10,
});
}
}
};
@endsection
@section('maincontent')
<div class="w-box w-box-orange">
	<div class="w-box-header">
		<h4>Başlıklar</h4>
	</div>
	<div class="w-box-content">
		<table id="dt_basic" class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>id</th>
					<th>Başlık / İçerik</th>
					<th>Ekleyen</th>
					<th>Eklenme Zamanı</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@forelse($topics as $topic)
				<tr>
					<td>{{$topic->id}}</td>
					<td>{{$topic->subject}}<br />{{$topic->content}}</td>
					<td>{{User::find($topic->poster_id)->full_name()}}</td>
					<td>{{$topic->created_at}}</td>
					<td>
						@if($topic->is_approved!=1)
						<a href="{{url('admin/forum/topics/approve/'.$topic->id)}}" title="Onayla"><i class="splashy-check"></i></a>
						@else
						<a href="{{url('admin/forum/topics/unapprove/'.$topic->id)}}" title="Onayı Kaldır"><i class="splashy-remove_outline"></i></a>
						@endif
					</td>
				</tr>
				@empty
				<tr><td colspan="9">Bu kriterlerde forum başlığı bulunmuyor</td></tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@endsection