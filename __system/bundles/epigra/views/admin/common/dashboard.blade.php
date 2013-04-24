@layout('epigra::admin.layout')
@section('title')
Dashboard
@endsection
@section('breadcrumbs')
@parent
<li><span>Dashboard</span></li>
@endsection

@section('maincontent')
<div class="span8">
	@include('epigra::system.alerts')
	<div class="w-box padding">
		<div class="w-box-header">
			<h4><i class="icon icon-list icon-white"></i> Makaleler</h4>
		</div>
		<div class="w-box-content">
			<div class="mbox_toolbar clearfix">
				<a href="#"><i class="icsw32-refresh"></i><span>Refresh</span></a>
				<a href="#"><i class="icsw32-create-write"></i><span>Compose</span></a>
				<a href="#"><i class="icsw32-pencil"></i><span>Answer</span></a>
				<a href="#"><i class="icsw32-bended-arrow-right"></i><span>Forward</span></a>
				<a href="#"><i class="icsw32-trashcan"></i><span>Delete</span></a>
			</div>
		</div>
	</div>


</div>
<div class="span4">
	<div class="w-box">
		<div class="w-box-header">
			<h4>Latest comments</h4>
			<i class="icsw16-speech-bubble icsw16-white pull-right"></i>
		</div>
		<div class="w-box-content">
			<table class="table table-striped table-list">
				<tbody>
					<tr>
						<td class="list-image"><a class="ptip_ne" href="#"><i class="splashy-comments"></i></a></td>
						<td>
							<a class="list-text" href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus dictum rhoncus...</a>
							<span class="minor">on October 24 @ 7:23</span>
						</td>
					</tr>
					<tr>
						<td class="list-image"><a class="ptip_ne" href="#"><i class="splashy-comments"></i></a></td>
						<td>
							<a class="list-text" href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus dictum rhoncus...</a>
							<span class="minor">on October 24 @ 7:23</span>
						</td>
					</tr>
					<tr>
						<td class="list-image"><a class="ptip_ne" href="#"><i class="splashy-comments"></i></a></td>
						<td>
							<a class="list-text" href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus dictum rhoncus. Duis quis pretium massa. Integer laoreet erat id neque interdum...</a>
							<span class="minor">on October 24 @ 7:23</span>
						</td>
					</tr>
					<tr>
						<td class="list-image"><a class="ptip_ne" href="#"><i class="splashy-comments"></i></a></td>
						<td>
							<a class="list-text" href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec cursus dictum rhoncus...</a>
							<span class="minor">on October 24 @ 7:23</span>
						</td>
					</tr>
					<tr>
						<td class="list-image"><a class="ptip_ne" href="#"><i class="splashy-comments"></i></a></td>
						<td>
							<a class="list-text" href="#">Lorem ipsum dolor sit amet...</a>
							<span class="minor">on October 24 @ 7:23</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="w-box w-box-orange">
		<div class="w-box-header">
			<h4>Epigra'dan Haberler</h4>
			<i class="icsw16-balloons icsw16-white  pull-right"></i>
		</div>
		<div class="w-box-content">
			<table class="table table-striped table-list">
				<tbody>
					<tr>
						<td class="list-image"><a class="ptip_ne" href="#"><i class="splashy-document_letter"></i></a></td>
						<td>
							<a class="list-text" href="#">Cardea v. 1.0.1 yayınlandı...</a>
							<span class="minor">25 Ocak 2013</span>
						</td>
					</tr>
					<tr>
						<td class="list-image"><a class="ptip_ne" href="#"><i class="splashy-star_boxed_full"></i></a></td>
						<td>
							<a class="list-text" href="#">Yenilenen kurumsal kimliğimiz ve web sitemizle karşınızdayız</a>
							<span class="minor">25 Ocak 2013</span>
						</td>
					</tr>
					
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection