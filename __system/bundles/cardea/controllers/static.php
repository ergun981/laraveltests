<?php

class Cardea_Static_Controller extends CardeaBase_Controller {

	public function get_dashboard() {
		Asset::container('header')->add('icsw2_32', 'assets/admin/img/icsw2_32/icsw2_32.css');	
		return View::make('cardea::common.dashboard');
	}

	public function get_faq() {
		Asset::container('footer')->add('jq-highlight', 'assets/admin/js/jquery.highlight.min.js','jQuery');
		Asset::container('footer')->add('beoro-help', 'assets/admin/js/pages/beoro_help_faq.js');
            
		return View::make('cardea::common.faq');
	}



}