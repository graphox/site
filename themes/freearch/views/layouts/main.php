<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Home - Home Page | Architecture - Free Website Template from Templates.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<link href="<?php echo Yii::app()->theme->baseUrl ?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->theme->baseUrl ?>/css/layout.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/js/cufon-yui.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/js/cufon-replace.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/js/Myriad_Pro_400.font.js" type="text/javascript"></script>
<script src="<?php echo Yii::app()->theme->baseUrl ?>/js/js/Myriad_Pro_600.font.js" type="text/javascript"></script>
<!--[if lt IE 7]>
	<link href="ie_style.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body id="page1">
<!-- header -->
<div id="header">
	<div class="container">
<!-- .logo -->
		<div class="logo">
			<a href="index.html"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/logo.gif" alt="" /></a>
		</div>
<!-- /.logo -->
		<form action="" id="search-form">
			<fieldset>
				<input type="text" class="text" /><input type="submit" value="Search" class="submit" />
			</fieldset>
		</form>
<!-- .nav -->

		<?php $this->widget('zii.widgets.CMenu',array(
			'htmlOptions' => array( 'class' => 'nav' ),
			'linkLabelWrapper' => 'span',
			'activeCssClass'	=> 'current',
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>

<!-- /.nav -->
<!-- .extra-box -->
		<div class="extra-box">
			<div class="inner">
				<h2>architectural<span>services for customers</span></h2>
				<ul>
					<li><a href="#">Structural engineering</a></li>
					<li><a href="#">Building services</a></li>
					<li><a href="#">Specialist consulting</a></li>
					<li><a href="#">Urban design</a></li>
					<li><a href="#">Transportation</a></li>
					<li><a href="#">Design development</a></li>
					<li><a href="#">Planning</a></li>
				</ul>
				<div class="wrapper"><a href="#" class="link1"><em><b>More Services</b></em></a></div>
			</div>
		</div>
<!-- /.extra-box -->
<!-- .intro-text -->
		<div class="intro-text">
			<h1>Accuracity<span>is the main feature</span><em>of our drawings</em></h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolo- re magna aliqua.</p>
			<div class="wrapper"><a href="#" class="button">View Our Works</a></div>
		</div>
<!-- /.intro-text -->
	</div>
</div>
<!-- content -->
<div id="content"><div class="inner_copy">More <a href="http://www.templatemonster.com/">Website Templates</a> at TemplateMonster.com!</div>
	<div class="container">
		<div class="wrapper">
			<div class="aside">
				<div class="indent">
					<h3>Categories</h3>
					<ul class="list1">
						<li><a href="#">Sed ut perspiciatis</a></li>
						<li><a href="#">Unde omnisiste</a></li>
						<li><a href="#">Natus errorsit</a></li>
						<li><a href="#">Voluptatem</a></li>
						<li><a href="#">Doloremque lauda</a></li>
						<li><a href="#">Accusantium</a></li>
						<li><a href="#">Totamrem aperiam</a></li>
						<li><a href="#">Eaque ipsa quae</a></li>
						<li><a href="#">Lnventore veritatis</a></li>
						<li><a href="#">Architecto</a></li>
					</ul>
				</div>
<!-- .box -->
				<div class="box">
					<h3>Login Form</h3>
					<form action="" id="login-form">
						<fieldset>
							<div class="field"><label for="text">Username:</label><input type="text" class="text" name="text"/></div>
							<div class="field"><label for="text">Password:</label><input type="password" class="password" name="text"/></div>
							<div class="wrapper">
								<input type="submit" value="Log In" class="submit fleft" />
								<div class="fright"><input type="checkbox" name="checkbox" id="checkbox" /><label for="checkbox">Remember</label></div>
							</div>
						</fieldset>
					</form>
				</div>
<!-- /.box -->
			</div>
			<div class="mainContent">
				<div class="article">
					<?php echo $content ?>
				</div>
				<?php /*
				<div class="article">
					<h2>Welcome<em>To our Architect website page</em></h2>
					<p><strong class="txt1">Healthy Building</strong> is a free web template created by TemplateMonster.com team. This website template is optimized for 1024X768 screen resolution.</p> 
					<div class="img-box">
						<img src="<?php echo Yii::app()->theme->baseUrl ?>/images/img1.jpg" alt="" />
						This website template can be delivered in two packages - with PSD source files included and without them. If you need PSD source files, please go to the template download page at TemplateMonster to leave the e-mail address that you want the template ZIP package to be delivered to.
					</div>
					This website template has several pages: <a href="index.html">Home</a>, <a href="about.html">About</a>, <a href="projects.html">Projects</a> (with <a href="project.html">Project Page</a>), <a href="contacts.html">Contacts</a> (note that contact us form – doesn’t work), <a href="sitemap.html">Site Map</a>.
				</div>
				*/ ?>
				<h3>Latest Projects</h3>
				<div class="wrapper">
					<div class="col-1">
						<div class="box1">
							<p><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/img2.jpg" alt="" /></p>
							<h4>Project 1</h4>
							<p class="p1">At vero eos et acmus et iusto odio dignis simos duci mus.</p>
							<div class="wrapper"><a href="#" class="link1"><em><b>Read More</b></em></a></div>
						</div>
					</div>
					<div class="col-2">
						<div class="box1">
							<p><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/img3.jpg" alt="" /></p>
							<h4>Project 2</h4>
							<p class="p1">Nalibero tempore cum soluta nobiest elige ndioptio.</p>
							<div class="wrapper"><a href="#" class="link1"><em><b>Read More</b></em></a></div>
						</div>
					</div>
					<div class="col-3">
						<div class="box1">
							<p><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/img4.jpg" alt="" /></p>
							<h4>Project 3</h4>
							<p class="p1">Temporibus uibusdam et aut officiis debitis aut rerum.</p>
							<div class="wrapper"><a href="#" class="link1"><em><b>Read More</b></em></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- footer -->
<div id="footer">
	<div class="container">
		<a href="http://www.templatemonster.com/" target="_blank">Website template</a> designed by TemplateMonster.com<br />
		<a href="http://www.templates.com/product/3d-models/" target="_blank">3D Models</a> provided by Templates.com
	</div>
</div>
<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>
