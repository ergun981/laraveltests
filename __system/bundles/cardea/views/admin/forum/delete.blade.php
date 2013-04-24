<div class="alert alert-block alert-error fade in">
	<form method="post" action="{{URL::to('admin/forum/categories/delete')}}">
		<input type="hidden" name="id" value="{{$id}}">
		<h4 class="alert-heading">Kategoriyi silmek üzeresiniz!</h4>
		<p>Kategoriyi silmeniz durumunda kategoriye ait tüm alt kategoriler, konular ve mesajlar da silinecektir. Silmek istediğinizden emin misiniz?</p>
		<p>
			<input type="submit" class="btn btn-danger" value="Evet"> <button type="button" class="btn" data-dismiss="modal">Hayır</button>
		</p>
	</form>
</div>