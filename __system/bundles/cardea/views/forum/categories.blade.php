@layout('admin.layout')
@section('title')
Forum Kategorileri
@endsection
@section('breadcrumbs')
@parent
<li><a href="#">Forum</a></li>
<li><span>Kategoriler</span></li>
@endsection

@section('jqueries')
$('#myModal').on('hidden', function () {
$(this).removeData ('modal');
});

@endsection
@section('maincontent')
<div class="span3">
	@include('admin.cms.pagesidebar')
</div>
<div class="span6">
	@include('system.alerts')
	<div class="w-box w-box">
		<div class="w-box-header"><h4>FORUMLAR</h4>
			<i class="icsw16-archive icsw16-white pull-right"></i>
		</div>
		<div class="w-box-content">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Title</th>
						<th width="40">Actions</th>
					</tr>
				</thead>
				<tbody>
					@forelse($forums_tree as $parent)
					<tr>
						<td>
							<a href="{{ URL::to('admin/forum/categories/edit/'.$parent['id']) }}">{{ $parent['title'] }}</a><br>
							<small>{{$parent['description'] }}</small>
						</td>
						<td>
							<a data-toggle="modal" data-remote="{{ URL::to('admin/forum/categories/edit/'.$parent['id']) }}" href="#myModal" data-id="{{$parent['id']}}"><i class="splashy-documents_edit"></i></a>
							<a data-toggle="modal" data-remote="{{ URL::to('admin/forum/categories/delete/'.$parent['id']) }}" href="#myModal" data-id="{{$parent['id']}}"<i class="splashy-document_letter_remove"></i></a>
						</td>	
					</tr>
					@if(!empty($parent['children'])) 

						@forelse($parent['children'] as $child)
						<tr>
							<td>
								&emsp; <a href="{{ URL::to('admin/forum/categories/edit/'.$child['id']) }}">{{ $child['title'] }}</a>
							</td>
							<td>
								<a data-toggle="modal" data-remote="{{ URL::to('admin/forum/categories/edit/'.$child['id']) }}" href="#myModal" data-id="{{$child['id']}}"><i class="splashy-documents_edit"></i></a>
								<a data-toggle="modal" data-remote="{{ URL::to('admin/forum/categories/delete/'.$child['id']) }}" href="#myModal" data-id="{{$child['id']}}"<i class="splashy-document_letter_remove"></i></a>
							</td>	
						</tr>
						@empty
						@endforelse
					@endif
					
					@empty
					<tr>
						<td colspan="2">Forum bulunmuyor</td>
					</tr>
					@endforelse

				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="span3">
	<div class="w-box">
		<div class="w-box-header">
			<h4>YENİ KATEGORİ</h4>
			<i class="icsw16-box-incoming
			icsw16-white pull-right"></i>
		</div>
		<div class="w-box-content">
			<form method="post" action="{{ URL::to('admin/forum/categories/new') }}">
				<div class="formSep">
					<label class="control-label req" for="parent">Parent</label>
					<?php echo Form::select('parent_id', $forums_dd  ,Input::get('parent_id'), array('id'=>"parent")); ?>
				</div>
				<div class="formSep">
					<label class="control-label req" for="title">Başlık</label>
					<input name="title" type="text" id="title" placeholder="Başlık">
				</div>
				<div class="formSep">
					<label class="control-label" for="description">Tanım</label>
					<textarea name="description" id="description" placeholder="Tanım"></textarea>
				</div>
				<div class="formSep"><input type="submit" value="Ekle" class="btn btn-primary"></div>
			</form>
		</div>
	</div>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h4>Forum Kategori İşlemleri</h4>
	</div>
	<div class="modal-body">
		<p>Yükleniyor</p>
	</div>

</div>
@endsection