<?php if(Yii::app()->user->hasFlash('edit-account')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('edit-account'); ?>
</div>

<?php else: ?>

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'account-edit-form',
		'enableAjaxValidation' => true,
		'enableClientValidation' => true
	)); ?>

		<h3>general user info</h3>
	
		<div class="form">
			<?=$form->errorSummary($model); ?>
		
			<fieldset>
				<strong style="display:block">Please contact an admin to change these fields.</strong>
				<div class="row">
					<?=$form->labelEx($model,'email'); ?>
					<?=$form->textField($model,'email', array('disabled' => 'disabled'))?>
					<?=$form->error($model,'email')?>
				</div>

				<div class="row">
					<?=$form->labelEx($model,'username'); ?>
					<?=$form->textField($model,'username', array('disabled' => 'disabled'))?>
					<?=$form->error($model,'username')?>

				</div>
			</fieldset>
				
				<div class="row">
					<?=$form->labelEx($model,'ingame_password'); ?>
					<?php if($model->status == User::OAUTH_ACCOUNT): ?>
						<strong>Please update your account to a full one to change this</strong>
						<?=$form->textField($model,'ingame_password', array('disabled' => 'disabled'))?>
					<?php else: ?>
						<?=$form->textField($model,'ingame_password')?>
					<?php endif; ?>
					<sub style="display:block">Due to the sauerbraten /setmaster hashing, this won't be stored encrypted in our database.</sub>
					<?=$form->error($model,'ingame_password')?>
				</div>

				<div class="row">
					<?=$form->labelEx($model,'web_password'); ?>
					<?php if($model->status == User::OAUTH_ACCOUNT): ?>
						<strong>Please update your account to a full one to change this</strong>
						<?=$form->passwordField($model,'web_password', array('value' => '', 'disabled' => 'disabled'))?>
					<?php else: ?>
						<?=$form->passwordField($model,'web_password', array('value' => ''))?>
					<?php endif; ?>
					<?=$form->error($model,'ingame_password')?>
				</div>

				<?php if($model->status != User::OAUTH_ACCOUNT): ?>
				<div class="row">
					<?=CHtml::label('retype password *', 'retype_password')?>
					<?=CHtml::passwordField('retype_password')?>
				</div>
				<?php endif; ?>
			</p>

			<div class="row">
				<?= CHtml::submitButton('save'); ?>
			</div>
		</div>
			<?php $this->endWidget(); ?>
<?php endif;?>
