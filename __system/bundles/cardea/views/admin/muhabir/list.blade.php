@layout('admin.layout')
@section('title')
Muhabir-Haber
@endsection
@section('breadcrumbs')
@parent
<li><span>Muhabir-Haber</span></li>
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

@section('maincontent')
<div class="span3">
	@include('admin.cms.pagesidebar')
</div>
<div class="span9">
	@include('system.alerts')
	<div class="w-box">
		<div class="w-box-header">
			<h4>Muhabir-Haber</h4>
			<i class="icsw16-speech-bubble icsw16-white pull-left"></i>
		</div>
		<div class="w-box-content">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Başlık</th>
						<th>Haber</th>
						<th>Muhabir</th>
						<th>Eylemler</th>
					</tr>
				</thead>
				<tbody>
					@forelse($haberler as $haber)
					<tr>
						<td><a href="{{ URL::to('admin/cms/articles/edit/'.$haber->article->id) }}">{{ $haber->article->article_langs[0]->title }} </a></td>
						<td>
							{{$haber->article->article_langs[0]->summary}}
						</td>
						<td>
							<a href="{{ URL::to('profile/'.$haber->article->author_id) }}"> {{$haber->article->author_name()}}</a>
						</td>
						<td>
							@if($haber->article->is_approved!=1)
							<a href="{{url('admin/muhabir-haber/approve/'.$haber->article->id)}}" title="Onayla"><i class="splashy-check"></i></a>
							@else
							<a href="{{url('admin/muhabir-haber/unapprove/'.$haber->article->id)}}" title="Onayı Kaldır"><i class="splashy-remove_outline"></i></a>
							@endif
							<a href="{{ URL::to('admin/cms/articles/edit/'.$haber->article->id) }}"><i class="splashy-documents_edit"></i></a>
							<a href="{{ URL::to('admin/cms/articles/delete/'.$haber->article->id) }}"><i class="splashy-document_letter_remove"></i></a>
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
