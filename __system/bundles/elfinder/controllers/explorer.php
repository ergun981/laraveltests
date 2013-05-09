<?php

class Elfinder_Explorer_Controller extends CardeaBase_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

	public function get_index(){
		Asset::container('header')->add('jqueryui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css');
		Asset::container('footer')->add('jquery-js','http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
		Asset::container('footer')->add('jqueryui-js','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js');
		Asset::container('header')->add('elfinder-css','bundles/elfinder/css/elfinder.min.css');
		Asset::container('header')->add('elfinder-theme','bundles/elfinder/css/theme.css');
		Asset::container('footer')->add('elfinder-js','bundles/elfinder/js/elfinder.min.js');
		Asset::container('footer')->add('elfinder-translation-tr','bundles/elfinder/js/i18n/elfinder.tr.js');
		return view('elfinder::explorer');

	}

	public function get_connector(){		
		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'explorer/elFinderConnector.class.php';
		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'explorer/elFinder.class.php';
		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'explorer/elFinderVolumeDriver.class.php';
		include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'explorer/elFinderVolumeLocalFileSystem.class.php';
		$opts = array(
					'debug' => true,
					'roots' => array(
						array(
							'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
							'path'          => '/var/www/epigra-cms/uploads',         // path to files (REQUIRED)
							'URL'           => url('uploads') , // URL to files (REQUIRED)
							'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
						)
					)
				);

				// run elFinder
				$connector = new elFinderConnector(new elFinder($opts));
				$connector->run();
	}
}