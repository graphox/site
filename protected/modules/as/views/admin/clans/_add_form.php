<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'clans-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => array('admin/clans/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/clans/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>


<fieldset>
		<label><?=$form->labelEx($form_model,'name'); ?></label>
		<?=$form->textField($form_model,'name')?>
		<?=$form->error($form_model,'name')?></fieldset>


<fieldset>
		<label><?=$form->labelEx($form_model,'description'); ?></label>
		<?=$form->textField($form_model,'description')?>
		<?=$form->error($form_model,'description')?></fieldset>




<fieldset>
		<label><?=$form->labelEx($form_model,'status'); ?></label>
		<?=$form->textField($form_model,'status')?>
		<?=$form->error($form_model,'status')?></fieldset>






<label for="Forum">Belonging Forum</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'forum',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="AclGroup">Belonging AclGroup</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'aclGroup',
							'fields' => 'name',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="Pages">Belonging Pages</label><?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'page',
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
