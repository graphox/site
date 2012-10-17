<!DOCTYPE html>
<html lang="<?=CHtml::encode(Yii::app()->language)?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?=CHtml::encode(Yii::app()->language)?>" />

	<link rel="stylesheet" type="text/css" href="http://assets.cookieconsent.silktide.com/current/style.min.css"/>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div><!-- id="mainmenu">-->
	<?php $this->widget('bootstrap.widgets.BootNavbar', array(
		'fixed'=>false,
		'brand'=>false,
		'collapse'=>true, // requires bootstrap-responsive.css
		'items'=>array(
		    array(
		        'class'=>'bootstrap.widgets.BootMenu',
		        'items'=>array(
		            array('label'=>'Home', 'url'=>array('/'), 'active'=>true),
		            array('label'=>'Blog', 'url'=>array('/blog'), 'items'=>array(
		                array('label'=>'Blog index', 'url'=>array('/blog')),
		                array('label'=>'Create blog', 'url'=>array('/blog/create')),
		                /*'---',
		                array('label'=>'NAV HEADER'),
		                array('label'=>'Separated link', 'url'=>'#'),
		                array('label'=>'One more separated link', 'url'=>'#'),*/
		            )),
		        ),
		    ),
		    //'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
		    array(
		        'class'=>'bootstrap.widgets.BootMenu',
		        'htmlOptions'=>array('class'=>'pull-right'),
		        'items'=>array(
		            /*array('label'=>'share', 'url'=>array('/share'), 'items'=>array(
						array('label'=> '<input>')
					)),*/
					(!Yii::app()->user->isGuest && Yii::app()->user->node->hasAccess('admin.user')) ?
						array('label'=>'Admin', 'url'=>array('/admin'), 'items'=>array(
							array('label'=>'user', 'url' => array('/admin/user')),
							array('label'=>'page', 'url' => array('/admin/page')),
						),
						
					) : '---',
		            array('label'=>'account', 'url'=>array('/user'), 'items'=>array(
						array('label'=>'Login', 'url'=>array('/user/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Register', 'url'=>array('/user/register'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.(Yii::app()->user->isGuest ? 'Guest' : Yii::app()->user->node->username).')', 'url'=>array('/user/logout'), 'visible'=>!Yii::app()->user->isGuest),
						
						array('label'=>'Profile', 'icon' =>'user', 'url'=>array('/user/profile'), 'visible'=>!Yii::app()->user->isGuest),
						//array('label'=>'settings', 'icon' => 'cog', 'url'=>array('/user/setting'), 'visible'=>!Yii::app()->user->isGuest),
						//array('label'=>'inbox', 'icon' => 'inbox', 'url'=>array('/pm/inbox'), 'visible'=>!Yii::app()->user->isGuest)
		            )),
		        ),
		    ),
		),
	)); ?>
	</div><!-- mainmenu -->
	
	<?php $this->widget('bootstrap.widgets.BootAlert'); ?>

	
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.BootBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<?php $this->widget('application.components.widgets.CmsElement', array(
		'template' => '<footer id="footer">{content}</footer>',
		'name' => 'Footer'
	))?>

</div><!-- page -->
<!-- Begin Cookie Consent -->
<script type="text/javascript" src="http://assets.cookieconsent.silktide.com/current/plugin.min.js"></script>
<script type="text/javascript">
// <![CDATA[
cc.initialise({
	cookies: {},
	settings: {
		bannerPosition: "push"
	}
});
// ]]>
</script>
<!-- End Cookie Consent plugin -->
</body>
</html>
