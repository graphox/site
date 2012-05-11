<header>
	<h3 class="tabs_involved"><?=$title?></h3>
	<ul class="tabs">
		<?php $first = true; ?>
		<?php foreach($tabs as $tab): ?>
		<li<?= ($first == true) ? ' class="active"' : '' ?>>
			<a href="#tab-<?=CHtml::encode(str_replace(' ', '-', $tab->name))?>"><?=CHtml::encode($tab->name)?></a>
		</li>
		<?php $first = false; ?>
		<?php endforeach ?>
	</ul>
</header>

<div class="tab_container">
<?php foreach($tabs as $tab): ?>
	<?=$tab->content?>
<?php endforeach; ?>
</div><!-- end of .tab_container -->

