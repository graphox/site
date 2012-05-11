<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'pages-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => array('pages/add'),
	'clientOptions' => array( 'validationUrl' => array('pages/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>



<fieldset>
			<label><?=$form->labelEx($form_model,'module'); ?></label>
		<?=$form->textField($form_model,'module')?>
		<?=$form->error($form_model,'module')?>	</fieldset>



<fieldset>
			<label><?=$form->labelEx($form_model,'uri'); ?></label>
		<?=$form->textField($form_model,'uri')?>
		<?=$form->error($form_model,'uri')?>	</fieldset>



<fieldset>
	</fieldset>



<fieldset>
	</fieldset>



<fieldset>
			<label><?=$form->labelEx($form_model,'title'); ?></label>
		<?=$form->textField($form_model,'title')?>
		<?=$form->error($form_model,'title')?>	</fieldset>



<fieldset>
			<label><?=$form->labelEx($form_model,'description'); ?></label>
		<?=$form->textArea($form_model,'description')?>
		<?=$form->error($form_model,'description')?>	</fieldset>



<fieldset>
			<label><?=$form->labelEx($form_model,'content'); ?></label>
		<?=$form->textArea($form_model,'content')?>
		<?=$form->error($form_model,'content')?>	</fieldset>



<fieldset>
			<label><?=$form->labelEx($form_model,'change_time'); ?></label>
		<?=$form->textArea($form_model,'change_time')?>
		<?=$form->error($form_model,'change_time')?>	</fieldset>



<fieldset>
	</fieldset>


<label for="Profile">Belonging Profile</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $model,
							'relation' => 'parent',
							'fields' => 'homepage',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="User">Belonging User</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $model,
							'relation' => 'editor',
							'fields' => 'username',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="AclObject">Belonging AclObject</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $model,
							'relation' => 'aclObject',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
