<?php foreach($breadcrumbs as $breadcrumb): ?>
<div class="breadcrumbs">
	<?=CHtml::link(
		$breadcrumb[0],
		$breadcrumb[1],
		isset($breadcrumb[2]) ?
			$breadcrumb[2] : array()
	)?>
</div>
<?php endforeach; ?>
