<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<link rel="stylesheet" href="<?=Yii::app()->theme->baseUrl?>/css/layout.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?=Yii::app()->theme->baseUrl?>/js/tabs.js"></script>
	<?php include dirname(__FILE__).'/partials/head.php'; ?>
	
	<script type="text/javascript">
	$(document).ready(function(){ 
      	  $(".tablesorter").tablesorter(); 
   	});
   	
	$(document).ready(function() {

		//When page loads...
		$(".tab_content").hide(); //Hide all content
		$("ul.tabs li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content

		//On Click Event
		$("ul.tabs li").click(function() {

			if ($(this).attr('data-pagination') == "true")
				return true
		
			$("ul.tabs li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab_content").hide(); //Hide all tab content

			var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active ID content
			return false;
		});

	});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>

</head>


<body>

	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.html">Website Admin</a></h1>
			<h2 class="section_title">Dashboard</h2><div class="btn_view_site"><a href="http://www.medialoot.com">View Site</a></div>
		</hgroup>
	</header> <!-- end of header bar -->
	
	<section id="secondary_bar">
		<div class="user">
			<p><?=CHtml::encode(Yii::app()->user->name)?> (<?=CHtml::link('3 Messages', array('//as/messages/inbox'))?>)</p>
			<?=CHtml::link('Logout', array('//as/auth/logout'), array('title' => 'Logout', 'class' => 'logout_user'))?>
		</div>
		<?php if(isset($this->breadcrumbs) && is_array($this->breadcrumbs)): ?>
			<?php $this->widget('as.components.UI.UIBreadcrumbContainer', array(
				'breadcrumbs' => $this->breadcrumbs,
				'view' => 'as.views.UI.admin.breadcrumbs'
			)); ?>
		<?php endif; ?>
	</section><!-- end of secondary bar -->
	
	<aside id="sidebar" class="column">
		<form class="quick_search">
			<input type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
		<hr/>
		<?php if(isset($this->menu) && is_array($this->menu)): ?>
			<?php $this->widget('as.components.UI.UIMenuWidget', array(
				'menu' => $this->menu,
				'view' => 'as.views.UI.admin.menu'
			)); ?>
		<?php endif; ?>
<?php /*
		<h3>Content</h3>
		<ul class="toggle">
			<li class="icn_new_article"><a href="#">New Article</a></li>
			<li class="icn_edit_article"><a href="#">Edit Articles</a></li>
			<li class="icn_categories"><a href="#">Categories</a></li>
			<li class="icn_tags"><a href="#">Tags</a></li>
		</ul>
		<h3>Users</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="#">Add New User</a></li>
			<li class="icn_view_users"><a href="#">View Users</a></li>
			<li class="icn_profile"><a href="#">Your Profile</a></li>
		</ul>
		<h3>Media</h3>
		<ul class="toggle">
			<li class="icn_folder"><a href="#">File Manager</a></li>
			<li class="icn_photo"><a href="#">Gallery</a></li>
			<li class="icn_audio"><a href="#">Audio</a></li>
			<li class="icn_video"><a href="#">Video</a></li>
		</ul>
		<h3>Admin</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="#">Options</a></li>
			<li class="icn_security"><a href="#">Security</a></li>
			<li class="icn_jump_back"><a href="#">Logout</a></li>
		</ul>
*/ ?>		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2011 Alphaserv</strong></p>
			<p>Theme by <a href="http://www.medialoot.com">MediaLoot</a></p>
		</footer>
	</aside><!-- end of sidebar -->
	
	<section id="main" class="column">
		<?php foreach(Yii::app()->user->getFlashes() as $key => $message)
			if ($key=='counters')
				continue;
			else
				echo '<h4 class="alert_'.$key.'">'.$message.'</h4>';
		?>
		<?php echo $content; ?>
	</section>


</body>

</html>
