@layout('admin.layout')
@section('title')
Yardım / SSS
@endsection
@section('breadcrumbs')
	@parent
    <li><span>Sık Sorulan Sorular</span></li>
@endsection

@section('maincontent')
<div class="span12">
	<div class="w-box w-box-green">
		<div class="w-box-header">
			<h4>Sık Sorulan Sorular</h4>
			<div class="pull-right">
				
				<a href="#" class="btn btn-inverse btn-mini" id="faq_btn"><span>Expand All</span><span style="display:none">Collapse All</span></a>
			</div>
		</div>
		<div class="w-box-content">
			<div class="faq_search_box">
				<input type="text" class="span12 faq_search" placeholder="Arama (min. 3 karakter)...">
				<span class="faq_count" style="display:none"></span>
			</div>
			<p id="faq_noresults" class="text-info" style="display:none">Sonuç Bulunamadı</p>
			<div class="accordion" id="faq_accordion">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" href="#faq_collapse_1">1. nisl eleifend arcu augue eros lobortis mauris venenatis, nisi curabitur sed nibh varius?</a>
					</div>
					<div id="faq_collapse_1" class="accordion-body collapse">
						<div class="accordion-inner">lacus auctor inceptos ut ligula nostra duis auctor ad, mollis ad phasellus aliquam dapibus euismod id eget, lacinia himenaeos dictum quis bibendum mollis primis</div>
					</div>
				</div>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" href="#faq_collapse_2">2. luctus platea senectus dui quis mi dictumst curabitur mattis ut duis, litora morbi mi pretium ligula conubia sollicitudin rhoncus egestas?</a>
					</div>
					<div id="faq_collapse_2" class="accordion-body collapse">
						<div class="accordion-inner">eleifend senectus euismod vehicula orci venenatis ultricies sodales, velit dapibus nisl amet sed</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection