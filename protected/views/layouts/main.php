<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

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
		'brand'=>'Project name',
		'brandUrl'=>'#',
		'collapse'=>true, // requires bootstrap-responsive.css
		'items'=>array(
		    array(
		        'class'=>'bootstrap.widgets.BootMenu',
		        'items'=>array(
		            array('label'=>'Home', 'url'=>'#', 'active'=>true),
		            array('label'=>'Link', 'url'=>'#'),
		            array('label'=>'Dropdown', 'url'=>'#', 'items'=>array(
		                array('label'=>'Action', 'url'=>'#'),
		                array('label'=>'Another action', 'url'=>'#'),
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
						array('label'=>'inbox', 'icon' => 'inbox', 'url'=>array('/pm/inbox'), 'visible'=>!Yii::app()->user->isGuest)
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

</body>
</html>
