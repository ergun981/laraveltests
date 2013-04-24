@layout('cardea::layout')
@section('title')
Makaleler
@endsection
@section('breadcrumbs')
@parent
<li><a href="#">İçerik Yönetim</a></li>
<li><span>Makaleler</span></li>
@endsection

@section('maincontent')
<div class="span3">
	@include('cardea::cms.pagesidebar')
</div>
<div class="span9">
	@include('cardea::system.alerts')
	<div class="w-box">
		<div class="w-box-header">
			<h4>Makaleler</h4>
			<i class="icsw16-speech-bubble icsw16-white pull-left"></i>
		</div>
		<div class="w-box-content">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Title</th>
						<th>Page</th>
						<th>Content</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($articles as $article)
					<tr>
						<td><a href="{{ URL::to('admin/cms/articles/edit/'.$article->id) }}">{{ $article->article_langs[0]->title }} </a></td>
						<td>
							<a href="{{ URL::to('admin/cms/pages/edit') }}">{{$article->page_articles[0]->page->page_langs[0]->title}}</a>
						</td>
						<td>
						@foreach($article->article_langs as $article_lang)
							<i class="flag-{{$article_lang->lang->flag}}"></i>
						@endforeach
							
						</td>
						<td>
							<a href="{{ URL::to('admin/cms/articles/edit/'.$article->id) }}"><i class="splashy-documents_edit"></i></a>
							<a href="{{ URL::to('admin/cms/articles/delete/'.$article->id) }}"><i class="splashy-document_letter_remove"></i></a>
						</td>
					</tr>
					@empty
					<tr><td colspan="4">Makale bulunmuyor.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection