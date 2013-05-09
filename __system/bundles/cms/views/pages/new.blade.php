@layout('cardea::layout')
@section('title')
Yeni Sayfa
@endsection
@section('breadcrumbs')
@parent
<li><a href="#">İçerik Yönetim</a></li>
<li><span>Yeni Sayfaa</span></li>
@endsection

@section('jqueries')
$(".onoffline").iButton({
resizeHandle: false,
labelOn: "Online",
labelOff: "Offline"   
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
			$(".pagename").val($('#url'+$(this).data('id')).val());
		});
	}      
});
	// listen for click
	$('form').on('submit', function(e) {
		$.post( $(this).attr('action'), $(this).serialize(), function(response) {
			//alert( response );
		});
		// disable default action
		e.preventDefault();
	});
@endsection
@section('maincontent')
<div class="span3">
	@include('cms::pagesidebar')
</div>
<div class="span9">
	@include('cardea::system.alerts')
	<div class="w-box w-box-red">
		<div class="w-box-header">
			<h4>Yeni Sayfa</h4>
			<i class="icsw16-create-write icsw16-white pull-left"></i>
		</div>
		<div class="w-box-content">
			{{Form::open(action('cms::pages@new'), 'POST', array('class' => 'form-horizontal'))}}
				<input class="pagename" type="hidden" name="name" value="{{Input::get('menu')}}">
				<div class="formSep row-fluid nopadding">
					<div class="span4">
						<label for="menu" class="req">Menü</label>
						<?php echo Form::select('menu_id', $menu_dd  ,Input::get('menu'), array('id'=>"menu")); ?>
					</div>
					<div class="span4">
						<label for="parent" class="req">Parent</label>
						<?php echo Form::select('parent_id', $pages_dd  ,Input::get('parent'), array('id'=>"parent")); ?>

					</div>
					<div class="span4">
						<label class="req" for="pageonline">Sayfa Online mı?</label>
						<input class="onoffline" type="checkbox" value="1" name="is_online" />
						<span class="help-block">Online olarak işaretlenmeyen sayfalar oluşturulur, fakat yayına alınmazlar</span>
					</div>
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
								<div class="control-group">
									<label class="control-label" for="title{{ $lang->id }}">Başlık</label>
									<div class="controls">
										<input class="forurl" data-id="{{ $lang->id }}" name="title[{{ $lang->id }}]" type="text" id="title{{ $lang->id }}" placeholder="Başlık">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="subtitle{{ $lang->id }}">Alt Başlık</label>
									<div class="controls">
										<input name="subtitle[{{ $lang->id }}]" type="text" id="subtitle{{ $lang->id }}" placeholder="Alt Başlık">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="nav_title{{ $lang->id }}">Menü Başlığı</label>
									<div class="controls">
										<input name="nav_title[{{ $lang->id }}]" type="text" id="nav_title{{ $lang->id }}" placeholder="Menü Başlığı">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="meta_title{{ $lang->id }}">Pencere Başlığı</label>
									<div class="controls">
										<input name="meta_title[{{ $lang->id }}]" type="text" id="meta_title{{ $lang->id }}" placeholder="Pencere Başlığı">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="url{{ $lang->id }}">URL</label>
									<div class="controls">
										<input class="thisurl" name="url[{{ $lang->id }}]" type="text" id="url{{ $lang->id }}" placeholder="URL">
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
</div>
@endsection