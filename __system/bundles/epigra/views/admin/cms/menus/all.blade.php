@layout('epigra::admin.layout')
@section('title')
Menüler
@endsection
@section('breadcrumbs')
@parent
<li><a href="#">İçerik Yönetim</a></li>
<li><span>Menü Yönetimi</span></li>
@endsection
@section('jqueries')

$('#menuform .close').click(function(){
data = '<div class="alert alert-block alert-error fade in">	<h4 class="alert-heading">Menüyü silmek üzeresiniz!</h4>	<p>Menüyü silmek istediğinizden emin misiniz?</p>	<p>		<button class="btn btn-danger" data-id="'+$(this).data('id')+'" id="confirmedDelete">Evet</button> <button type="button" class="btn" data-dismiss="modal">Hayır</button>	</p></div>';
$('.modal-body').html(data);
});

$('#betaModal').on('shown', function () {
	$('#confirmedDelete').click(function(){
		var curId = $(this).data('id');
		$.get('{{URL::to('admin/cms/menus/delete/')}}'+$(this).data('id'), function(data){
			if(data == '1'){	
				$.sticky('Menü silindi!', {'type'			: 'st-success'});
				$('#menuform .close[data-id='+curId+']').parent().delay(400).slideUp().remove();
			}
			else $.sticky('Menü silinemedi.<br />Lütfen tekrar deneyin.', {'type'			: 'st-error'});
		});
		$('#betaModal').modal('hide');

	});
});
@endsection
@section('maincontent')
<div class="span3">
	@include('epigra::admin.cms.pagesidebar')
</div>
<div class="span6">
	@include('epigra::system.alerts')

	<div class="w-box padding">
		<div class="w-box-header">
			<h4>Menüler</h4>
			<i class="icsw16-speech-bubble icsw16-white pull-right"></i>
		</div>
		<div class="w-box-content">
			{{Form::open('admin/cms/menus/update', NULL, array('class' => 'form-horizontal', 'id' => 'menuform'))}}
				@forelse($menus as $menu)
				<div class="well">
					<a class="close" href="#betaModal" data-toggle="modal" data-id="{{$menu->id}}">x</a>
					<div class="control-group">
						<label class="control-label" for="id{{$menu->id}}">ID</label>
						<div class="controls">
							<input name="menu[{{$menu->id}}][id]" type="text" id="id{{$menu->id}}" value="{{$menu->id}}" disabled="disabled">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="title{{$menu->id}}">Başlık</label>
						<div class="controls">
							<input name="menu[{{$menu->id}}][title]" type="text" id="title{{$menu->id}}" placeholder="Başlık" value="{{$menu->title}}">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="name{{$menu->id}}">Ad</label>
						<div class="controls">
							<input name="menu[{{$menu->id}}][name]" type="text" id="name{{$menu->id}}" placeholder="Ad" value="{{$menu->name}}">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="order{{$menu->id}}">Sıra</label>
						<div class="controls">
							<input name="menu[{{$menu->id}}][order]" type="text" id="order{{$menu->id}}" value="{{$menu->order}}">
						</div>
					</div>
				</div>
			@empty
			Henüz menü bulunmuyor.
			@endforelse
			<?php if(!empty($menus)):?>
			<div class="formSep">
				<button class="btn btn-primary" type="submit">Kaydet</button>
			</div>
			<?php endif; ?>
			<div class="clearfix"></div>
		{{Form::close()}}
	</div>
</div>
</div>
<div class="span3">
	<div class="w-box">
		<div class="w-box-header">
			<h4>Menü Ekle</h4>
		</div>
		<div class="w-box-content">
			{{Form::open('admin/cms/menus/new')}}
			<div class="formSep">
				<label class="req control-label" for="title">Menü Başlığı</label>
				<input type="text" name="title" id="title" value="{{Input::old('title')}}">
			</div>
			<div class="formSep">
				<label class="req control-label" for="name">Menü Adı</label>
				<input type="text" name="name" id="name" >
			</div>
			<div class="formSep">
				<label class="req control-label" for="order">Sıra</label>
				<input type="text" name="order" id="order" >
			</div>
			<div class="formSep">
				<input type="submit" value="Ekle" class="btn btn-primary">
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
<div id="betaModal" class="modal hide fade">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>Silinmeyi Onaylayın</h3>
	</div>
	<div class="modal-body">
		Yükleniyor...
	</div>
</div>
@endsection