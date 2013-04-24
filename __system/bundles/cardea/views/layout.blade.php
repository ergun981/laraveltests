<!DOCTYPE HTML>
<html lang="en-US">
<head>

	<meta charset="UTF-8">
	<title>
		@yield('title')
		- {{ Config::get('project.name') }} Yönetim  </title>
		<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<link rel="icon" type="image/ico" href="{{URL::to_asset('assets/admin/favicon.ico')}}">


		{{Asset::container('header')->styles()}}
		{{Asset::container('header')->scripts()}}

		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie8.css"><![endif]-->
		<!--[if IE 9]><link rel="stylesheet" href="css/ie9.css"><![endif]-->

		<!--[if lt IE 9]>
			<script src="js/ie/html5shiv.min.js"></script>
			<script src="js/ie/respond.min.js"></script>
			<script src="js/lib/flot-charts/excanvas.min.js"></script>
			<![endif]-->

		</head>
		<body>
			<!-- main wrapper (without footer) -->    
			<div class="main-wrapper">
				<!-- top bar -->
				<div class="navbar navbar-fixed-top">
					<div class="navbar-inner">
						<div class="container">
							<div class="pull-right top-search">
								<form action="" >
									<input type="text" name="q" id="q-main">
									<button class="btn"><i class="icon-search"></i></button>
								</form>
							</div>
							<div id="fade-menu" class="pull-left">
								<ul class="clearfix" id="mobile-nav">
									<li><a href="{{ action('cardea::dashboard') }} "><i class="icon icon-th-large icon-white"></i> Dashboard</a>
										<li><a href="#"><i class="icon icon-list icon-white"></i> İçerik Yönetimi</a>
											<ul>
												<li>
													{{ HTML::link_to_route('menus', 'Menüler'); }}
												</li>
												<li>
													{{ HTML::link_to_route('new_page', 'Yeni Sayfa'); }}
												</li>
												<li>
													{{ HTML::link_to_route('list_articles', 'Makalaler'); }}
												</li>
											</ul>
										</li>
										<li><a href="#"><i class="icon icon-bookmark icon-white"></i> Forum</a>
											<ul>
												<li>
													{{ HTML::link_to_action('cardea::admin.forum.categories@index', 'Kategoriler')}}
												</li>
												<li>
													<a href="{{url('admin/forum/topics/unapproved_list')}}">Onay Bekleyenler</a>
												</li>
												
											</ul>
										</li>
										<li><a href="#"><i class="icon icon-user icon-white"></i> Kullanıcılar</a>
											<ul>
												<li>
													<a href="{{url('admin/users')}}">Üye Listesi</a>
												</li>
												
											</ul>
										</li>
										<li>
											<a href="#"><i class="icsw16-white icsw16-ruler"></i> {{Config::get('project.name')}}</a>
											<ul>
												<li>
													<a href="{{url('admin/olgular')}}">Olgular</a>
													<ul>
														<li><a href="{{url('admin/olgular/unapproved')}}">Onay Bekleyenler</a>
														</ul>
													</li>
													<li>
														<a href="{{url('admin/muhabir-haber')}}">Muhabir Haber</a>
														<ul>
															<li>
																<a href="#">Onay Bekleyenler</a>
															</li>
															<ul>
																<li>
																	<a href="{{url('admin/muhabir-haber/unapproved_authors')}}">Muhabirler</a>
																</li>
																<li>
																	<a href="{{url('admin/muhabir-haber')}}">Haberler</a>
																</li>
															</ul>
														</ul>
													</li>
												</ul>
											</li>
											<li><a href="#"><i class="icon icon-wrench icon-white"></i> Araçlar</a></li>
											<li><a href="#"><i class="icon icon-tasks icon-white"></i> Sistem Ayarları</a>
												<ul>
													<li><a href="#">Cardea</a>
														<ul>
															<li><a href="#">Site Ayarları</a></li>
															<li><a href="#">Gelişmiş Ayarlar</a></li>
														</ul>
													</li>
													<li><a href="setting">Kullanıcılar</a></li>
													<li><a href="setting">Diller</a></li>
												</ul>
											</li>
											<li><a href="{{ URL::to('admin/faq') }} "><i class="icon icon-question-sign icon-white"></i> Sık Sorulan Sorular</a></li>


										</ul>
									</div>
								</div>
							</div>
						</div>

						<!-- header -->
						<header>
							<div class="container">
								<div class="row">
									<div class="span3">
										<div class="main-logo"><a href="{{ URL::to('admin/dashboard') }}">{{ HTML::image('assets/admin/img/epigra_yatay.png', 'Epigra'); }}</a></div>
									</div>
									<div class="span5">
										<nav class="nav-icons">
											<ul>
												<li><a href="{{url('dashboard')}}" class="ptip_s" title="Anasayfa"><i class="icsw16-home"></i></a></li>
												<li><a href="{{url('admin/olgular/unapproved')}}" class="ptip_s" title="Olgu"><i class="icsw16-tags-2"></i><span class="badge badge-info">1</span></a></li>
												<li><a href="{{url('admin/forum/topics/unapproved_list')}}" class="ptip_s" title="Forum Konu"><i class="icsw16-create-write"></i><span class="badge badge-info">2</span></a></li>
												<li><a href="{{url('admin/muhabir-haber/unapproved_authors')}}" class="ptip_s" title="Muhabir"><i class="icsw16-admin-user"></i><span class="badge badge-important">3</span></a></li>
												<li><a href="{{url('admin/muhabir-haber')}}"> <span class="ptip_s" title="Muhabir-Haber"><i class="icsw16-file-cabinet"></i><span class="badge badge-important">4</span></a></li>
												<li><a href="#" class="ptip_s" title="Ayarlar"><i class="icsw16-cog"></i></a></li>
											</ul>
										</nav>
									</div>
									<div class="span4">
										<div class="user-box">
											<div class="user-box-inner">
												{{ HTML::image('assets/admin/img/avatars/avatar.png', 'Avatar',array('class' => 'user-avatar img-avatar')); }}
												<div class="user-info">
													{{ __('admin.merhaba',array('name' => 'Emrullah Yazlı')) }}
													<ul class="unstyled">
														<li>{{ HTML::link('admin/profile', __('admin.settings') ); }}</li>
														<li>&middot;</li>
														<li>{{ HTML::link('admin/logout', __('admin.logout') ); }} </li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</header>

						<!-- breadcrumbs -->
						<div class="container">
							<ul id="breadcrumbs">
								@section('breadcrumbs')
								<li><a title="{{ __('admin.homepage') }}" href="{{ URL::base() }} "><i class="icon-home"></i></a></li>
								<li>{{ HTML::link('admin', __('admin.adminpanel') ); }}</li>
								@yield_section
							</ul>
						</div>

						<!-- main content -->
						<div class="container">
							<div class="row-fluid">
								@yield('maincontent')
							</div>
						</div>
						<div class="footer_space"></div>
					</div> 

					<!-- footer --> 
					<footer>
						<div class="container">
							<div class="row">
								<div class="span12">
									<div>&copy; Epigra <?php echo date('Y');?> - <?php echo Config::get('cardea.fwname');?> v.<?php echo Config::get('cardea.version'); ?></div>
								</div>
							</div>
						</div>
					</footer>

					<!-- Common JS -->
					{{Asset::container('footer')->scripts()}}

					<script type="text/javascript">
					$(document).ready(function() {
						@yield("jqueries")
					});
					</script>
				</body>
				</html>