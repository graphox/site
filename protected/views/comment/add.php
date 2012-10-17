<?php
$form = $this->beginWidget('bootstrap.widgets.BootActiveForm',array( 
	'id'=>'comment-form', 
	'enableAjaxValidation'=>true,
	'action' => array('/comment/create', 'id' => $parentId)
));
				
	echo '<p class="help-block">Fields with <span class="required">*</span> are required.</p>';

	echo $form->errorSummary($model);

	echo $form->textFieldRow($model,'title', array('class'=>'span5'));
	echo $form->textAreaRow($model,	'contentSource', array('class'=>'span8'));

	if(isset($returnUrl))
		echo \CHtml::hiddenField('returnUrl', $returnUrl);

	echo '<div class="form-actions">';
		$this->widget('bootstrap.widgets.BootButton', array( 
			'buttonType'=>'submit', 
			'type'=>'primary', 
			'label'=>'Create comment.', 
		));
	echo '</div>';
	
$this->endWidget();