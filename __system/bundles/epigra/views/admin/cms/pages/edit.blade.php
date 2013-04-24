@layout('admin.layout')
@section('title')
Sayfa Düzenleme
@endsection
@section('breadcrumbs')
@parent
<li><a href="#">İçerik Yönetim</a></li>
<li><span>Sayfa Düzenleme</span></li>
@endsection

@section('jqueries')
$(".test").iButton({
resizeHandle: false
});
$(".pageonline").iButton({
resizeHandle: false
});
$(function() {
$('.datetime').datetimepicker({
language: 'tr-TR'
});
});
@endsection
@section('maincontent')
<div class="span3">
	@include('admin.cms.pagesidebar')
</div>
<div class="span6">
	@include('system.alerts')
	<div class="w-box w-box-red">
		<div class="w-box-header">
			<h4>Sayfa Düzenle : {{$page->name}}</h4>
			<i class="icsw16-create-write icsw16-white pull-left"></i>
		</div>
		<div class="w-box-content">
			<form class="form-horizontal" method="post" action="{{URL::to('admin/cms/pages/update_langs/'.$page->id)}}">

				<div class="formSep">
					<div class="tabbable tabbable-bordered">
						<ul class="nav nav-tabs">
							<?php $is_first = true; ?>
							@foreach ($languages as $lang)
							<li <?php if($is_first) echo 'class="active"'; $is_first= false; ?>>
								<a data-toggle="tab" href="#lang-{{ $lang->id }}"> <i class="flag-<?php echo $lang->flag; ?>"></i> {{ $lang->name }}</a>
							</li>
							@endforeach     
						</ul>
						<div class="tab-content">
							<?php $is_first = true; ?>
							@foreach ($languages as $lang)
							<?php $pagelang = $page->page_langs()->where('lang_id', '=', $lang->id)->first()?>
							<div id="lang-{{ $lang->id }}" class="tab-pane <?php if($is_first) echo 'active'; $is_first= false; ?>">
								<div class="control-group">
									<label class="control-label" for="title{{ $lang->id }}">Başlık</label>
									<div class="controls">
										<input class="forurl" data-id="{{ $lang->id }}" name="{{ '_'.$lang->id }}[title]" type="text" id="title{{ $lang->id }}" placeholder="Başlık" value="{{$pagelang->title}}">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="subtitle{{ $lang->id }}">Alt Başlık</label>
									<div class="controls">
										<input name="{{ '_'.$lang->id }}[subtitle]" type="text" id="subtitle{{ $lang->id }}" placeholder="Alt Başlık" value="{{$pagelang->subtitle}}">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="nav_title{{ $lang->id }}">Menü Başlığı</label>
									<div class="controls">
										<input name="{{ '_'.$lang->id }}[nav_title]" type="text" id="nav_title{{ $lang->id }}" placeholder="Menü Başlığı" value="{{$pagelang->nav_title}}">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="meta_title{{ $lang->id }}">Pencere Başlığı</label>
									<div class="controls">
										<input name="{{ '_'.$lang->id }}[meta_title]" type="text" id="meta_title{{ $lang->id }}" placeholder="Pencere Başlığı" value="{{$pagelang->meta_title}}">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="url{{ $lang->id }}">URL</label>
									<div class="controls">
										<input disabled  name="{{ '_'.$lang->id }}[url]" type="text" id="url{{ $lang->id }}" placeholder="URL" value="{{$pagelang->url}}">
									</div>
								</div>

							</div>
							@endforeach
							
						</div>
					</div>
				</div>

				<div class="formSep">
					<button type="submit" class="btn btn-primary">Kaydet</button>
				</div>
			</form>
		</div>
	</div>

	<div class="w-box w-box-red">
		<div class="w-box-header">
			<h4>Makaleler</h4>
			<i class="icsw16-create-write icsw16-white pull-left"></i>
			<a href="{{URL::to('admin/cms/articles/new/'.$page->id)}}" class="btn btn-small btn-primary pull-right" title="Article Ekle"><i class="icon-plus icon-white"></i>Makale Ekle</a>
		</div>
		<div class="w-box-content">
			<div role="grid" class="dataTables_wrapper form-inline" id="dt_gal_wrapper">	
				<div class="dt-wrapper">
					<table class="table table-vam table-striped dataTable" aria-describedby="dt_gal_info">
						<thead>
							<tr role="row">
								<th style="width:13px" class="table_checkbox sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
									<input type="checkbox" data-tableid="dt_gal" class="select_rows" name="select_rows">
								</th>
								<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="Başlık">Başlık</th>
								<th class="sorting" role="columnheader" tabindex="0" aria-controls="dt_gal" rowspan="1" colspan="1" aria-label="Yayınlanma Tarihi">Yayınlanma Tarihi</th>
								<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="Eylemler">Eylemler</th>
							</tr>
						</thead>
						<tbody role="alert" aria-live="polite" aria-relevant="all">
							@foreach ($page->page_articles as $page_article)
							<tr class="odd">
								<td class=" "><input type="checkbox" class="row_sel" name="row_sel"></td>
								<td class="  sorting_1">{{$page_article->article->article_langs[0]->title}}</td>
								<td class=" ">{{$page_article->article->publish_on}}</td>
								<td class=" ">
									<div class="btn-group">
										<a title="Düzenle" class="btn btn-mini" href="{{URL::to('admin/cms/articles/edit/'.$page_article->article->id)}}"><i class="icon-pencil"></i></a>
										<a title="Sil" class="btn btn-mini" href="{{URL::to('admin/cms/articles/delete/'.$page_article->article->id)}}"><i class="icon-trash"></i></a>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="dt-row dt-bottom-row">
					<div class="dataTables_info" id="dt_gal_info"></div>
					<div class="dataTables_paginate paging_bootstrap pagination">
						<ul>
							<li class="prev disabled">
								<a href="#">Previous</a>
							</li>
							<li class="active">
								<a href="#">1</a>
							</li>
							<li class="next disabled">
								<a href="#">Next</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="span3">
	<div class="w-box w-box-red">
		<div class="w-box-header">
			<h4>Detaylar</h4>
			<i class="icsw16-create-write icsw16-white pull-left"></i>
		</div>
		<div class="w-box-content">
			<form class="form-horizontal" method="post" action="{{URL::to('admin/cms/pages/update_details/'.$page->id)}}">

				<div class="formSep">
					<label class="req" for="pageonline">Durum</label>
					 {{Form::checkbox('is_online', '1', Input::old('is_online', $page->is_online), ['class' => 'pageonline'])}}
				</div>
				<div class="formSep">
					<label>Oluşturulma Tarihi:</label>{{$page->created_at}}
				</div>

				<div class="formSep">
					<label>Son Güncellenme Tarihi:</label>{{$page->updated_at}}
					
				</div>
				<div class="formSep">
					<label class="req" for="publish_at">Yayınlanma Tarihi</label>
					<div class="input-prepend date datetime">
						<span class="add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
						</span>
						<input name="publish_on" data-format="yyyy-MM-dd hh:mm:ss" class="span10" type="text" value="{{$page->publish_on}}"></input>
					</div>
				</div>

				<div class="formSep">
					<label class="req" for="unpublish_at">Yayından Kaldırılma Tarihi</label>
					<div class="input-prepend date datetime">
						<span class="add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
						</span>
						<input name="publish_off" data-format="yyyy-MM-dd hh:mm:ss" class="span10" type="text" value="{{$page->publish_off}}"></input>
					</div>
				</div>

				<div class="formSep">
					<label class="req" for="redirect_link">Link</label>
					<textarea  name="link">{{$page->link}}</textarea>
					
				</div>
				<div class="formSep">
					
					<label for="menu_id" class="req">Menü</label>
					{{Form::select('menu_id', $menu_dd, $page->menu_id)}}
				</div>
				<div class="formSep">
					<label for="parent_id" class="req">Parent</label>
					{{Form::select('parent_id', $pages_dd, $page->parent_id)}}
				</div>
				<div class="formSep">
					<button type="submit" class="btn btn-primary">Kaydet</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection