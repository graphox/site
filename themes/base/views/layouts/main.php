<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?php
		// Suppress Yii's autoload of JQuery
		// We're loading it at bottom of page (best practice)
		// from Google's CDN with fallback to local version 
		Yii::app()->clientScript->scriptMap=array(
			'jquery.js'=>false,
		);
		
		// Load Yii's generated javascript at bottom of page
		// instead of the 'head', ensuring it loads after JQuery
		Yii::app()->getClientScript()->coreScriptPosition = CClientScript::POS_END;
	?>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/jquery-1.7.2.min.js"><\/script>')</script>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width">
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-and-responsive.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
	
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/modernizr-2.5.3-respond-1.1.0.min.js"></script>
</head>
<body>

<div class="container">
	<div class="row">
		<header class="span12">
			<div id="header-top" class="row">
				<div class="span4">
					<a href="<?php echo Yii::app()->request->baseUrl; ?>/">
						<img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/yii.png" alt="" />
					</a>
				</div>
				<div class="span8">
					<p style="text-align:right;">
						Call now on 555 555 555<br />
						Follow us on <a class="badge badge-info" href="#" target="_blank">Twitter</a>
					</p>
				</div>
			</div>

			<div class="navbar">
				<?php $this->widget('bootstrap.widgets.BootNavbar', array(
					'fixed'=>false,
					'brand'=>'Sauers',
					'brandUrl'=>$this->createUrl('/'),
					'collapse'=>true, // requires bootstrap-responsive.css
					'items'=>array(
						array(
							'class'=>'bootstrap.widgets.BootMenu',
							'items'=>array(
								array('label'=>'Home', 'url' => $this->createUrl('/')),
								array('label'=>'Link', 'url'=>'#'),
								array('label'=>'Admin', 'url'=>array('/admin'), 'items'=>array(
								    array('label'=>'User management', 'url'=>array('/admin/user/admin')),
								    array('label'=>'Entity management', 'url'=>array('/admin/entity/admin')),
								    array('label'=>'Something else here', 'url'=>'#'),
								    '---',
								    array('label'=>'NAV HEADER'),
								    array('label'=>'Separated link', 'url'=>'#'),
								    array('label'=>'One more separated link', 'url'=>'#'),
								)),
							),
						),
						'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
						array(
							'class'=>'bootstrap.widgets.BootMenu',
							'htmlOptions'=>array('class'=>'pull-right'),
							'items'=>array(
								array('label'=>'share', 'url'=>array('/share')),
								'---',
								array('label'=>'account', 'url'=>array('/user'), 'items'=>array(
									array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
									array('label'=>'Login', 'url'=>array('/user/register'), 'visible'=>Yii::app()->user->isGuest),
									array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest),
						
									array('label'=>'Profile', 'icon' =>'user', 'url'=>array('/user/profile'), 'visible'=>!Yii::app()->user->isGuest),
									array('label'=>'settings', 'icon' => 'cog', 'url'=>array('/user/setting'), 'visible'=>!Yii::app()->user->isGuest),
									array('label'=>'inbox', 'icon' => 'inbox', 'url'=>array('/pm/inbox'), 'visible'=>!Yii::app()->user->isGuest),
									array('label' => '<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>')
								)),
							),
						),
					),
				)); ?>
			</div><!-- navbar -->
	
			<?php $this->widget('bootstrap.widgets.BootAlert'); ?>
	
			<?php if(isset($this->breadcrumbs)):?>
				<?php $this->widget('zii.widgets.CBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
					'htmlOptions'=>array('class'=>'breadcrumbs breadcrumb'),
				)); ?><!-- breadcrumbs -->
			<?php endif?>
		</header>
	</div>
	
	<div class="row">
		<div class="span12">
			<?php echo $content; ?>
		</div>
	</div>

	<div class="row">
		<footer class="span12">
			<?php $this->widget('application.components.widgets.CmsElement', array(
				'template' => '<div class="row">
									<div class="span8">
										<p>Copyright &copy; '.date('Y').' killme - All Rights Reserved.</p>
									</div>
									<div class="span4"><p>{content}</p></div>
								</div>',
				'name' => 'Footer'
			))?>
		</footer>
	</div><!-- footer -->

</div><!-- container -->
<?php /*<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/bootstrap/bootstrap.min.js"></script>*/ ?>
<?php /*<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/script.js"></script>*/ ?>
<?php /*<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>*/ ?>
</body>
</html>
