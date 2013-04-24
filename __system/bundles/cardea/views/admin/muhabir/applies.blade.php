@layout('admin.layout')
@section('title')
Muhabir-Haber
@endsection
@section('breadcrumbs')
@parent
<li><span>Muhabir Başvuruları</span></li>
@endsection

@section('maincontent')
<div class="span3">
	@include('admin.cms.pagesidebar')
</div>
<div class="span9">
	@include('system.alerts')
	<div class="w-box">
		<div class="w-box-header">
			<h4>Muhabir Başvuruları</h4>
			<i class="icsw16-speech-bubble icsw16-white pull-left"></i>
		</div>
		<div class="w-box-content">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Profil Fofo</th>
						<th>İsim-Soyisim</th>
						<th>Eylemler</th>
					</tr>
				</thead>
				<tbody>
					@forelse($activities as $activity)
					<tr>
						<td>
							@if(!empty($activity->user->profile_photo))
							<img class="img-avatar" alt="" src="{{URL::to_asset('images/timthumb.php?src=profile_photos/'.$activity->user->profile_photo.'&w=80&h=80')}}">
							@else
							<img class="img-avatar" alt="" src="img/avatars/avatar.png">
							@endif
						</td>
						<td>
							<a href="{{ URL::to('profile/'.$activity->user->id) }}"> {{$activity->user->full_name()}}</a>
						</td>
						<td>
							<a href="{{url('admin/muhabir-haber/approve_author/'.$activity->id)}}" title="Onayla"><i class="splashy-check"></i></a>
							<a href="{{url('admin/cms/articles/unapprove_author/'.$activity->id)}}" title="Onayı Kaldır"><i class="splashy-remove_outline"></i></a>
						</td>
					</tr>
					@empty
					<tr><td colspan="4">Haber bulunmuyor.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
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