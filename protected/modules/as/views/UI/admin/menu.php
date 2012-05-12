<?php foreach($menu as $subject): ?>
	<h3><?=CHtml::encode($subject[0])?></h3>
	<ul class="toggle">
		<?php foreach($subject[1] as $item): ?>
			<li class="icn_<?=CHtml::encode(str_replace(' ', '_', isset($item[2]) ? $item[2] : $item[0]))?>">
				<?=CHtml::link($item[0], $item[1])?>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endforeach; ?>
