<!-- ENDS menu-holder -->
<div id="menu-holder">
	<!-- wrapper-menu -->
	<div class="wrapper">
		<!-- Navigation -->
		<ul id="nav" class="sf-menu">
			<?php foreach($menu as $subject): ?>
				<li class="current-menu-item">
					<a>
						<?=CHtml::encode($subject[0])?>
						<span class="subheader"></span>
					</a>
					<ul>
						<?php foreach($subject[1] as $item): ?>
							<li class="icn_<?=CHtml::encode(str_replace(' ', '_', isset($item[2]) ? $item[2] : $item[0]))?>">
								<?=CHtml::link($item[0], $item[1])?>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
		</ul>
		<!-- Navigation -->
	</div>
	<!-- wrapper-menu -->
</div>
<!-- ENDS menu-holder -->


