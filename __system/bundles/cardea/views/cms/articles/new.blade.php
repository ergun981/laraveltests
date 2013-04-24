@layout('cardea::layout')
@section('title')
Yeni Makale
@endsection
@section('breadcrumbs')
@parent
<li><a href="#">İçerik Yönetim</a></li>
<li><a href="{{URL::to('admin/cms/articles')}}">Makaleler</a></li>
<li><span> Yeni Makale</span></li>
@endsection
@section('jqueries')
$(".active").iButton({
resizeHandle: false
});
$(function() {
$('.datetime').datetimepicker({
language: 'tr-TR'
});
});

var slug = function(str) {
str = str.replace(/^\s+|\s+$/g, ''); // trim
str = str.toLowerCase();

var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;&ğşı";
var to   = "aaaaaeeeeeiiiiooooouuuunc-------gsi";
for (var i=0, l=from.length ; i<l ; i++) {
str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
}

str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
.replace(/\s+/g, '-') // collapse whitespace and replace by -
.replace(/-+/g, '-'); // collapse dashes

return str;
};

$(".forurl").each(function(i){

$(this).keyup(function(){
var x = slug( $(this).val() );
$('#url'+$(this).data('id')).val(x); 
}); 

if(i==0){
$(this).blur(function() {
$(".articlename").val($('#url'+$(this).data('id')).val());
});
}      
});

@endsection
@section('maincontent')
<div class="span3">
	@include('cardea::cms.pagesidebar')
</div>
<div class="span9">
	@include('cardea::system.alerts')
	<div class="w-box">
		<div class="w-box-header">
			<h4>Yeni Makale</h4>
			<i class="icsw16-create-write icsw16-white pull-right"></i>
		</div>
		<div class="w-box-content">
			{{Form::open_for_files(action('cardea::cms.articles.new'), 'POST', array('class'=>'form-horizontal'))}}
			<!-- <form class="form-horizontal" method="post" action="{{URL::to('admin/cms/articles/new')}}"> -->
			<input type="hidden" name="page_id" value="{{$page_id}}">
			<input type="hidden" name="name" class="articlename">
			<div class="formSep">
				<div class="span5">
					<label class="req" for="publish_on">Publish Date</label>
					<div class="input-prepend date datetime">
						<span class="add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
						</span>
						<input data-format="yyyy-MM-dd hh:mm:ss" class="span10" type="text" name="publish_on"></input>
					</div>
				</div>
				<div class="span5">
					<label class="req" for="publish_off">Unpublish Date</label>
					<div class="input-prepend date datetime">
						<span class="add-on">
							<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
						</span>
						<input data-format="yyyy-MM-dd hh:mm:ss" class="span10" type="text" name="publish_off"></input>
					</div>
				</div>
				<div class="span2">
					<label class="req" for="pageonline">Durum</label>
					<input class="active" type="checkbox" name="is_online" value="1" />
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
						<div id="lang-{{ $lang->id }}" class="tab-pane <?php if($is_first) echo 'active'; $is_first= false; ?>">
							<div class="row-fluid">
								<div class="span7">
									<div class="control-group">
										<label class="control-label" for="title{{ $lang->id }}">Başlık</label>
										<div class="controls">
											<input class="forurl" data-id="{{$lang->id}}" type="text" id="title{{ $lang->id }}" placeholder="Başlık" name="lang[{{$lang->id}}][title]">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="subtitle{{ $lang->id }}">Alt Başlık</label>
										<div class="controls">
											<input type="text" id="subtitle{{ $lang->id }}" placeholder="Alt Başlık" name="lang[{{$lang->id}}][subtitle]">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="url{{ $lang->id }}">URL</label>
										<div class="controls">
											<input type="text" id="url{{ $lang->id }}" placeholder="URL" name="lang[{{$lang->id}}][url]">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="window_title{{ $lang->id }}">Pencere Başlığı</label>
										<div class="controls">
											<input type="text" id="window_title{{ $lang->id }}" placeholder="Pencere Başlığı" name="lang[{{$lang->id}}][window_title]">
										</div>
									</div>
								</div>
								<div class="span5">
									<div class="fileupload fileupload-new"data-provides="fileupload">
										<div class="fileupload-new thumbnail"style="width:260px;height:180px;">
											<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=resim+yok"/>
										</div>
										<div class="fileupload-preview fileupload-exists thumbnail" style="width:200px; height:150px; line-height:20px;">        
										</div>
										<div>
											<span class="btn btn-file">
												<span class="fileupload-new">Resmi Sec</span>
												<span class="fileupload-exists">Degister</span>
												<input id="image{{ $lang->id }}" name="image" type="file"/>
											</span>
											<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Kaldir</a>
										</div>
									</div>
								</div>
							</div>
							<hr />
							<label for="summary{{ $lang->id }}">Özet</label>
							<textarea class="span12" rows="5" id="summary{{ $lang->id }}" name="lang[{{$lang->id}}][summary]"></textarea>
							<hr />
							<label for="content{{ $lang->id }}">Metin</label>
							<textarea class="ckeditor" id="content{{ $lang->id }}" name="lang[{{$lang->id}}][content]"></textarea>
							
						</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="formSep">
				<button type="submit" class="btn btn-primary">Kaydet</button>
			</div>

		</div>
	</div>
	{{Form::close()}}
</div>
</div>
</div>
@endsection