<!-- ENDS menu-holder -->
<div id="menu-holder">
	<!-- wrapper-menu -->
	<div class="wrapper">
		<!-- Navigation -->
		<ul id="nav" class="sf-menu">
			<?php foreach($menu as $subject): ?>
				<li class="current-menu-item">
					<a>
						<?php
							list($line1, $line2) = array('', '');
							@list($line1, $line2) = explode("\n", $subject[0]); #fu php
						?>
						<?=CHtml::encode($line1)?>
						<span class="subheader"><?=$line2?></span>
					</a>
					<ul>
						<?php foreach($subject[1] as $item): ?>
							<li class="icn_<?=CHtml::encode(str_replace(' ', '_', isset($item[2]) ? (is_array($item[2]) ? (isset($item[2]['icon']) ? $item[2]['icon'] : $item[0] ) : $item[2])  : $item[0]))?>">
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


