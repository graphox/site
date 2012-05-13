<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'pages-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/pages/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/pages/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<?=$form->labelEx($form_model,'module'); ?>		<?=$form->textField($form_model,'module')?>
		<?=$form->error($form_model,'module')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'uri'); ?>		<?=$form->textField($form_model,'uri')?>
		<?=$form->error($form_model,'uri')?></fieldset>






<fieldset>
		<?=$form->labelEx($form_model,'title'); ?>		<?=$form->textField($form_model,'title')?>
		<?=$form->error($form_model,'title')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'description'); ?>		<?=$form->textArea($form_model,'description')?>
		<?=$form->error($form_model,'description')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'allow_comments'); ?>		<?=$form->textField($form_model,'allow_comments')?>
		<?=$form->error($form_model,'allow_comments')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'layout'); ?>		<?=$form->textField($form_model,'layout')?>
		<?=$form->error($form_model,'layout')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'content'); ?>		<?=$form->textArea($form_model,'content')?>
		<?=$form->error($form_model,'content')?></fieldset>


<fieldset>
		<?=$form->labelEx($form_model,'change_time'); ?>		<?=$form->textArea($form_model,'change_time')?>
		<?=$form->error($form_model,'change_time')?></fieldset>




<label for="User">Belonging User</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'editor',
							'fields' => 'username',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="AclObject">Belonging AclObject</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'aclObject',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="Pages">Belonging Pages</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'parent',
							'fields' => 'module',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
