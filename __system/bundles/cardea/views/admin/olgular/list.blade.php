@layout('admin.layout')
@section('breadcrumbs')
@parent
<li><span> Olgular</span></li>
@endsection
@section('jqueries')


@endsection
@section('maincontent')
<div class="w-box w-box-orange">
	<div class="w-box-header">
		<h4>OLGULAR</h4>
	</div>
	<div class="w-box-content">
		<table id="dt_basic" class="table table-striped table-condensed">
			<thead>
				<tr>
					<th>id</th>
					<th width="100">Görsel</th>
					<th>Başlık / İçerik</th>
					<th>Ekleyen</th>
					<th>Eklenme Zamanı</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@forelse($olgular->results as $olgu)
				<tr>
					<td>{{$olgu->id}}</td>
					<td><img src="{{URL::to_asset('images/timthumb.php?src=questions/'.$olgu->image.'&h=80&w=80')}}" /></td>
					<td>{{$olgu->title}}<br />{{$olgu->content}}</td>
					<td>{{$olgu->user->full_name()}}</td>
					<td>{{$olgu->created_at}}</td>
					<td>
						@if($olgu->is_approved!=1)
						<a href="{{url('olgular/approve/'.$olgu->id)}}" title="Onayla"><i class="splashy-check"></i></a>
						@else
						<a href="{{url('olgular/unapprove/'.$olgu->id)}}" title="Onayı Kaldır"><i class="splashy-remove_outline"></i></a>
						@endif
					</td>
				</tr>
				@empty
				<tr><td>0</td><td colspan="6">Bu kriterlerde olgu bulunmuyor</td></tr>
				@endforelse
				<tr><td colspan="6">{{$olgular->links()}}</td></tr>
			</tbody>
		</table>
	</div>
</div>
@endsection