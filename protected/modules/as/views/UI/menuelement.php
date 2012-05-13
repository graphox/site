<!-- Navigation -->
<ul <?php if($top_level):?>id="nav" class="sf-menu"<?php endif; ?>>
<?php foreach($elements as $element): ?>
<li>
	<a href="<?=CHtml::normalizeUrl($element->url)?>">
		<?php if($top_level): ?>
		<?=CHtml::encode($element->title)?>
		<span class="subheader"><?=CHtml::encode($element->subtitle)?></span>
		<?php else: ?>
			<span><?=CHtml::encode($element->title)?></span>
		<?php endif; ?>
	</a>
	<?php if(count($element->children) > 0): ?>
	<ul>
		<?php 
			$top_level = false;
			$elements = $element->children;
			
			Yii::app()->controller->renderPartial('as.views.UI.menuelement', get_defined_vars());
		?>
	</ul>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<!-- Navigation -->
