<!DOCTYPE  html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?=CHtml::encode($this->pageTitle)?></title>
		
		<!-- CSS -->
		<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/style.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/social-icons.css" type="text/css" media="screen" />

		<?php include dirname(__FILE__).'/head.php'; ?>
		<!-- tabs -->
		<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/tabs.css" type="text/css" media="screen" />
		<script type="text/javascript" src="<?=Yii::app()->theme->baseUrl?>/js/tabs.js"></script>
		<!-- ENDS tabs -->
	</head>
	<body class="home">
<?php /*
Yii::app()->getPanel('adminPanel')->show(array(
    'visibleRule' => 'Yii::app()->user->hasAccess("admin")', // this panel visible only admin
    'height' => 120, // this panel is 120px  height
    'useCookie' => false, // this panel use cookiew
    'initiallyOpen' => false // this panel is closed by default
));
*/ ?>
	
	
		<!-- HEADER -->
		<div id="header">
			<!-- wrapper-header -->
			<div class="wrapper">
				<a href="index.html"><img id="logo" src="<?=Yii::app()->theme->baseUrl?>/img/logo.png" alt="<?=CHtml::encode(Yii::app()->name); ?>" /></a>
					<!-- search -->
					<div class="top-search">
						<form  method="get" id="searchform" action="#">
							<div>
								<input type="text" value="Search..." name="s" id="s" onfocus="defaultInput(this)" onblur="clearInput(this)" />
								<input type="submit" id="searchsubmit" value=" " />
							</div>
						</form>
					</div>
					<!-- ENDS search -->
				</div>
				<!-- ENDS wrapper-header -->					
			</div>
			<!-- ENDS HEADER -->
			
			
			<!-- Menu -->
			<div id="menu">
					
			
				<!-- ENDS menu-holder -->
				<div id="menu-holder">
					<!-- wrapper-menu -->
					<div class="wrapper">
						<!-- Navigation -->
						<ul id="nav" class="sf-menu">
							<li class="current-menu-item"><a href="index.html">Home<span class="subheader">Welcome</span></a></li>
							<li><a href="features.html">Features<span class="subheader">Awesome options</span></a>
								<ul>
									
									<li><a href="features-columns.html"><span> Columns layout</span></a></li>
									<li><a href="features-accordion.html"><span> Accordion</span></a></li>
									<li><a href="features-toggle.html"><span> Toggle box</span></a></li>
									<li><a href="features-tabs.html"><span> Tabs</span></a></li>
									<li><a href="features-infobox.html"><span> Text box</span></a></li>
									<li><a href="features-monobox.html"><span> Icons</span></a></li>
									<li><a href="features.html">Features<span class="subheader">Awesome options</span></a>
										<ul>
									
											<li><a href="features-columns.html"><span> Columns layout</span></a></li>
											<li><a href="features-accordion.html"><span> Accordion</span></a></li>
											<li><a href="features-toggle.html"><span> Toggle box</span></a></li>
											<li><a href="features-tabs.html"><span> Tabs</span></a></li>
											<li><a href="features-infobox.html"><span> Text box</span></a></li>
											<li><a href="features-monobox.html"><span> Icons</span></a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li><a href="blog.html">Blog<span class="subheader">Read our posts</span></a></li>
							<li><a href="portfolio.html">Portfolio <span class="subheader">Showcase work</span></a></li>
							<li><a href="gallery.html">Gallery<span class="subheader">Featured work</span></a>
								<ul>
									<li><a href="gallery.html"><span> Four columns</span></a></li>
									<li><a href="gallery-3.html"><span> Three columns </span></a></li>
									<li><a href="gallery-2.html"><span> Two columns </span></a></li>
									<li><a href="video-gallery.html"><span> Video gallery </span></a></li>
								</ul>
							</li>
							<li><a href="contact.html">Contact<span class="subheader">Get in touch</span></a></li>
						</ul>
						<!-- Navigation -->
					</div>
					<!-- wrapper-menu -->
				</div>
				<!-- ENDS menu-holder -->
			</div>
			<!-- ENDS Menu -->
