<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'comment-votes-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/votes/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/votes/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>






<fieldset>
		<?=$form->labelEx($form_model,'value'); ?>		<?=$form->textField($form_model,'value')?>
		<?=$form->error($form_model,'value')?></fieldset>


<label for="User">Belonging User</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'user',
							'fields' => 'username',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="PageComments">Belonging PageComments</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'comment',
							'fields' => 'title',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
