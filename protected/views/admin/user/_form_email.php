<?php if($isNew): ?>
	<div class="alert alert-block alert-notice fade in">
		<a class="close" data-dismiss="alert">×</a>
		<strong>User not yet created!</strong> You may only add email addresses after creating the user.
	</div>
<?php else: ?>
	<?php
		$form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
	));?>
		<?=$form->errorSummary($email_model)?>
		
		<?=$form->textFieldRow($email_model, 'email', array('prepend'=>'@'));?>
		<?=$form->dropDownListRow($email_model, 'status', $email_model->statusOptions); ?>

		<?= $form->checkboxRow($email_model, 'is_primary'); ?>
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'reset', 'icon'=>'remove', 'label'=>'Reset'));?>
			<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>'Submit'));?>
		</div>
	<?php $this->endWidget(); ?>
<?php endif; ?>
