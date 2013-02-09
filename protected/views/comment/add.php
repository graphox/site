<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
	'id'=>'comment-form', 
	'enableAjaxValidation'=>true,
	'action' => array('/comment/create', 'id' => $parentId)
));
				
	echo '<p class="help-block">Fields with <span class="required">*</span> are required.</p>';

	echo $form->errorSummary($model);

	echo $form->textFieldRow($model,'title', array('class'=>'span8'));
	echo $form->markdownEditorRow($model,'contentSource', array('class'=>'span6'));

	if(isset($returnUrl))
		echo \CHtml::hiddenField('returnUrl', $returnUrl);

	echo '<div class="form-actions">';
		$this->widget('bootstrap.widgets.TbButton', array( 
			'buttonType'=>'submit', 
			'type'=>'primary', 
			'label'=>'Create comment.', 
		));
	echo '</div>';
	
$this->endWidget();