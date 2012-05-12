<?php if(isset($buttons) && count($buttons) > 0): ?>
	<ul class="pager"><!--normal template style paginator -->
	<?php foreach($buttons as $button): ?>
		<?php if(isset($button[2]) && $button[2] === true): ?>
			<li class="active">
		<?php else: ?>
			<li class="<?=CHtml::encode($button[0]).'-page'?>" data-pagination="true" >
		<?php endif; ?>
	<?php
		switch($button[0])
		{
			case 'first': 
			case 'previous':
			case 'next':
			case 'last': ?>
				<?=CHtml::link(Yii::t('as.widget.paginator', $button[0]), $button[1], isset($button[3]) ? $button[3] : array()) ?>		
			<?php break;
		
			default: ?>
				<?=CHtml::link($button[0], $button[1], isset($button[3]) ? $button[3] : array()) ?>			
			<?php break;
		} ?>
		</li>
	<?php endforeach; ?>
	</ul><!--END normal template style paginator -->
<?php endif; ?>
