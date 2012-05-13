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
//*/ ?>
	
	
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
				<?php /**/ ?>
					<?php $this->widget('as.components.UI.UIDbMenuWidget', array()); ?>
				<?php //*/ ?>
			</div>
			<!-- ENDS Menu -->
