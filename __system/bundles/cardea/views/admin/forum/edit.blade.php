<form method="post" action="{{ URL::to('admin/forum/categories/edit/'.$c->id) }}">
	<input type="hidden" name="category_id" value="{{$c->id}}">
	<div class="formSep">
		<label class="control-label req" for="parent">Parent</label>
		<?php echo Form::select('parent_id', $forums_dd  , $c->parent_id, array('id'=>"parent")); ?>
	</div>
	<!--<?php print_r($forums_dd);?>-->
	<div class="formSep">
		<label class="control-label req" for="title">Başlık</label>
		<input q="title" type="text" id="title" value="{{ $c->title }}" name="title">
	</div>
	<div class="formSep">
		<label class="control-label" for="description">Tanım</label>
		<textarea q="description" id="description" name="description">{{ $c->description }}</textarea>
	</div>
	<div class="formSep"><input type="submit" value="Kaydet" class="btn btn-primary pull-right"><div class="clearfix"></div></div>
</form>