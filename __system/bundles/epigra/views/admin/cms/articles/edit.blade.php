@layout('admin.layout')
@section('title')
Makale Düzenle
@endsection
@section('breadcrumbs')
@parent
<li><a href="#">İçerik Yönetim</a></li>
<li><a href="{{URL::to('admin/cms/articles')}}">Makaleler</a></li>
<li><span>Makale Düzenle</span></li>
@endsection
@section('jqueries')
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
<div class="span9">
	<div class="w-box">
		<div class="w-box-header">
			<h4>Makale Düzenle</h4>
			<i class="icsw16-create-write icsw16-white pull-right"></i>
		</div>
		<div class="w-box-content">
			{{Form::open_for_files('admin/cms/articles/edit', 'POST', array('class'=>'form-horizontal'))}}
			<input type="hidden" name="article_id" value="{{$article->id}}">
			<div class="formSep">
				<div class="span3">
					<label>Oluşturulma Tarihi:</label>
					{{$article->created_at}}
				</div>
				<div class="span3">
					<label>Son Düzenlenme Tarihi:</label>
					{{$article->updated_at}}
				</div>
				<div class="span3">
					<label>Yayınlanma Tarihi:</label>
					{{$article->publish_on}}
				</div>
				<div class="span3">
					<label>Yayından Kaldırma Tarihi:</label>
					{{$article->publish_off}}
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="formSep">
				<div class="span2">
					<label class="req" for="pageonline">Durum</label>
					<input class="pageonline" type="checkbox" name="is_online" value="1" <?php if($article->page_articles()->first()->is_online) echo 'checked="checked"';?>/>
				</div>

				<div class="span3">
					<label class="req" for="publish_on">Publish Date</label>
					<div class="input-prepend date datetime" disabled="disabled">
						<span class="add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
						</span>
						<input data-format="yyyy-MM-dd hh:mm:ss" class="span10" type="text" value="{{$article->publish_on}}" name="publish_on"></input>
					</div>
				</div>

				<div class="span3">
					<label class="req" for="publish_off">Unpublish Date</label>
					<div class="input-prepend date datetime">
						<span class="add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
						</span>
						<input data-format="yyyy-MM-dd hh:mm:ss" class="span10" type="text" value="{{$article->publish_off}}" name="publish_off"></input>
					</div>
				</div>

				<div class="span4">
					<label class="req" for="redirect_link">Link</label>
					<textarea></textarea>

				</div>
				<div class="clearfix"></div>
			</div>
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
						<?if (isset($langcontents[$lang->id]->id)):?><input type="hidden" name="lang[{{$lang->id}}][article_lang_id]" value="<?php echo $langcontents[$lang->id]->id; ?>"><?php endif; ?>
						<div id="lang-{{ $lang->id }}" class="tab-pane <?php if($is_first) echo 'active'; $is_first= false; ?>">
							<div class="row-fluid">
								<div class="span7">
									<div class="control-group">
										<label class="control-label" for="title{{ $lang->id }}">Başlık</label>
										<div class="controls">
											<input class="forurl" data-id="{{$lang->id}}" type="text" id="title{{ $lang->id }}" placeholder="Başlık" name="lang[{{$lang->id}}][title]" value="<?php if(isset($langcontents[$lang->id]->title)) echo $langcontents[$lang->id]->title; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="subtitle{{ $lang->id }}">Alt Başlık</label>
										<div class="controls">
											<input type="text" id="subtitle{{ $lang->id }}" placeholder="Alt Başlık" name="lang[{{$lang->id}}][subtitle]" value="<?php if(isset($langcontents[$lang->id]->subtitle)) echo $langcontents[$lang->id]->subtitle; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="url{{ $lang->id }}">URL</label>
										<div class="controls">
											<input type="text" id="url{{ $lang->id }}" placeholder="URL" name="lang[{{$lang->id}}][url]" value="<?php if(isset($langcontents[$lang->id]->url)) echo $langcontents[$lang->id]->url; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="window_title{{ $lang->id }}">Pencere Başlığı</label>
										<div class="controls">
											<input type="text" id="window_title{{ $lang->id }}" placeholder="Pencere Başlığı" name="lang[{{$lang->id}}][window_title]" value="<?php if(isset($langcontents[$lang->id]->meta_title)) echo $langcontents[$lang->id]->meta_title; ?>">
										</div>
									</div>
								</div>
								<div class="span5">
									<div class="fileupload fileupload-exists"data-provides="fileupload">
										<div class="fileupload-preview thumbnail" style="width: 260px; height: 180px;">
											@if(!empty($langcontents[$lang->id]->image))
											<img src="{{URL::to_asset('images/timthumb.php?src=articles/'.$langcontents[$lang->id]->image.'&w=260&h=180')}}">
											@else
											<img src="http://placehold.it/260x180/0eafff/ffffff.png">
											@endif
										</div>
										<div>
											<span class="btn btn-file">
												<span class="fileupload-new">Resmi Seç</span>
												<span class="fileupload-exists">Değiştir</span>
												<input id="image{{ $lang->id }}" name="image" type="file"/>
											</span>
											<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Kaldır</a>
										</div>
									</div>
								</div>
							</div>
							<hr />
							<label for="summary{{ $lang->id }}">Özet</label>
							<textarea class="span12" rows="5" id="summary{{ $lang->id }}" name="lang[{{$lang->id}}][summary]"><?php if(isset($langcontents[$lang->id]->summary)) echo $langcontents[$lang->id]->summary; ?></textarea>
							<hr />
							<label for="content{{ $lang->id }}">Metin</label>
							<textarea class="ckeditor" id="content{{ $lang->id }}" name="lang[{{$lang->id}}][content]"><?php if(isset($langcontents[$lang->id]->content)) echo $langcontents[$lang->id]->content; ?></textarea>
						</div>
						@endforeach

					</div>
				</div>
			</div>

			<div class="formSep">
				<button type="submit" class="btn btn-primary pull-right">Düzenle</button>
				<div class="clearfix"></div>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@endsection