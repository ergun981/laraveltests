@layout('cardea::layout')
@section('title')
Dosya Yönetimi
@endsection
@section('breadcrumbs')
@parent
<li><a href="{{action('elfinder::')}}">Dosya Yönetimi</a></li>
@endsection

@section('maincontent')
		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>
@endsection

@section('jqueries')
	<!-- elFinder initialization (REQUIRED) -->
		$().ready(function() {
			var elf = $('#elfinder').elfinder({
				url : '{{url('elfinder/php/connector')}}',  // connector URL (REQUIRED)
				//lang: 'tr',             // language (OPTIONAL)
			}).elfinder('instance');
		});
@endsection