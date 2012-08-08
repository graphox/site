<?php $this->beginContent('//layouts/main'); ?>
<div class="span8">
	<?php echo $content; ?>
</div>
<div class="span3">
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
			$this->widget('bootstrap.widgets.BootMenu', array(
				'type' => 'list',
				'items' => $this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
		$this->endWidget();
	?>
	
	<?php if(isset($this->secMenu)): ?>
		<div class="well">
			<?php $this->widget('bootstrap.widgets.BootMenu', array(
				'type'=>'list',
				'items'=>$this->secMenu
			)); ?>
		</div>
	<?php endif; ?>
	
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>
