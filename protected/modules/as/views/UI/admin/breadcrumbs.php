<div class="breadcrumbs_container">
	<article class="breadcrumbs">
		<?php $first = true; ?>
		<?php foreach($breadcrumbs as $breadcrumb): ?>
			<?php if(!$first): ?>
				<div class="breadcrumb_divider"></div>
			<?php else: 
				$first = false;
				  endif; ?>
			<?=CHtml::link(
				$breadcrumb[0],
				$breadcrumb[1],
				isset($breadcrumb[2]) ?
					$breadcrumb[2] : array()
			)?>
		<?php endforeach; ?>
	</article>
</div>
