<?php
	/** @var \TbActiveForm $form */
	$form = $this->beginwidget('ext.bootstrap.widgets.TbActiveForm', array(
		'action' => array('/file/edit', 'id' => $model->id)
	));
?>
	<div class="span4">
		<p><strong>Note:</strong> changing this may cause a lot of broken links.</p>
		<?php echo $form->textFieldRow($model, 'routeName'); ?>
	</div>

	<div class="span8 last">
		<?php echo CHtml::image($model->url, $model->name, array('class' => 'span8')); ?>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Submit'
		)); ?>
	</div>
<?php $this->endWidget(); ?>