<?php include_once(dirname(__FILE__).'/partials/top.php');?>
			<!-- MAIN -->
			<div id="main">
				<!-- wrapper-main -->
				<div class="wrapper">
					<?php foreach(Yii::app()->user->getFlashes() as $key => $message)
						if ($key=='counters')
							continue;
						else
							echo '<div class="flash-'.$key.' '.$key.'-box '.$key.'-add">'.$message.'</div>';
					?>
					<?php echo $content; ?>
				</div>
				<!-- ENDS wrapper-main -->
			</div>
			<!-- ENDS MAIN -->
<?php include_once(dirname(__FILE__).'/partials/bottom.php');?>	

