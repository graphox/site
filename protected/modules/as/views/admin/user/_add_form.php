<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'user-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => array('admin/user/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/user/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<label><?=$form->labelEx($form_model,'username'); ?></label>
		<?=$form->textField($form_model,'username')?>
		<?=$form->error($form_model,'username')?></fieldset>


<fieldset>
		<label><?=$form->labelEx($form_model,'ingame_password'); ?></label>
		<?=$form->textField($form_model,'ingame_password')?>
		<?=$form->error($form_model,'ingame_password')?></fieldset>


<fieldset>
		<label><?=$form->labelEx($form_model,'email'); ?></label>
		<?=$form->textField($form_model,'email')?>
		<?=$form->error($form_model,'email')?></fieldset>


<fieldset>
		<label><?=$form->labelEx($form_model,'hashing_method'); ?></label>
		<?=$form->textField($form_model,'hashing_method')?>
		<?=$form->error($form_model,'hashing_method')?></fieldset>


<fieldset>
		<label><?=$form->labelEx($form_model,'web_password'); ?></label>
		<?=$form->textField($form_model,'web_password')?>
		<?=$form->error($form_model,'web_password')?></fieldset>


<fieldset>
		<label><?=$form->labelEx($form_model,'salt'); ?></label>
		<?=$form->textField($form_model,'salt')?>
		<?=$form->error($form_model,'salt')?></fieldset>


<fieldset>
		<label><?=$form->labelEx($form_model,'status'); ?></label>
		<?=$form->textField($form_model,'status')?>
		<?=$form->error($form_model,'status')?></fieldset>


<label for="AclGroup">Belonging AclGroup</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'aclGroups',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'checkbox',
							)
						); ?><label for="Profile">Belonging Profile</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'profile',
							'fields' => 'homepage',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
