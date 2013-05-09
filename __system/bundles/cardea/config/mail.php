<?php 
return array(
	//for register action
	'register' => array(
		'to'=>array('admin'),
		'subject' => 'Yeni Üye Kaydı',
		'body' => ''),
	// //////////////////////////

	//for apply-for-author action
	'apply-for-author' => array(
		'to'=>array('admin'),
		'subject' => 'Muhabir Başvurusu',
		'body' => ''),
	// //////////////////////////

	//for editor-article
	'editor-article'=> array(
		'to'=>array('admin'),
		'subject' => 'Muhabir-Haber Paylaşımı',
		'body' => ''),
	// //////////////////////////

	//for olgu-share
	'olgu-share' => array( 
		'to' => array('urun_muduru', 'medikal_mudur'),
		'subject'=> 'Yeni Olgu Paylaşımı',
		'body' => ''),
	// //////////////////////////

	//for forumTitle-open
	'forumTitle-open' => array( 
		'to' => array('urun_muduru', 'medikal_mudur'),
		'subject'=> 'Forumda Yeni Konu Başlığı  Açma',
		'body' => ''
		),
	// //////////////////////////
	//for follow
	'follow' => array( 
		'to' => '',
		'subject'=> 'Takipçi İsteği',
		'body' => ''
		),
	// //////////////////////////
	);