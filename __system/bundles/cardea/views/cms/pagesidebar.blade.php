<div class="w-box">
	<div class="w-box-header">
		<i class="icsw16-book-large icsw16-white pull-left"></i> <h4>Sayfalar</h4>
	</div>
	<div class="w-box-content">
		<div class="accordion" id="cms_menu">
			<?php $is_first = true; ?> 

			@foreach ($menus as $menu)
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#cms_menu" href="#menu-{{ $menu->id }}">
						{{ $menu->title }}
					</a>
				</div>
				<div id="menu-{{ $menu->id }}" class="accordion-body collapse <?php if($is_first) echo 'in'; $is_first= false; ?>">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							@forelse($menu->pages as $mypage)
								<?php $page = $mypage->page_langs[0];   ?>
								<li class="nav-header"><a href="{{action('cardea::cms.pages.edit', array($mypage->id))}}">{{ $page->title }}</a></li>
							@empty
								<li class="nav-header">Sayfa Bulunmuyor</li>
							@endforelse

						</ul>
					</div>
				</div>
			</div>
			@endforeach
			
			
		</div>
	</div>	
</div>
