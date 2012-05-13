<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'page-comments-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/comments/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/comments/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>






<fieldset>
		<?=$form->labelEx($form_model,'title'); ?>		<?=$form->textField($form_model,'title')?>
		<?=$form->error($form_model,'title')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'content'); ?>		<?=$form->textArea($form_model,'content')?>
		<?=$form->error($form_model,'content')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'posted_date'); ?>		<?=$form->textArea($form_model,'posted_date')?>
		<?=$form->error($form_model,'posted_date')?></fieldset>


<label for="Pages">Belonging Pages</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'page',
							'fields' => 'module',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="User">Belonging User</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'user',
							'fields' => 'username',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
