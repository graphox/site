<h1>Account registration</h1>
	
<?php if(Yii::app()->user->hasFlash('register.success')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('register.success'); ?>
		<?=CHtml::link('Click here to go to the activation page', array('//as/auth/activate'))?>
	</div>
<?php else: ?>
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'register-form',
			'enableAjaxValidation'=>true,
		)); ?>

			<p class="note">Fields with <span class="required">*</span> are required.</p>

			<?php echo $form->errorSummary($model); ?>

			<div class="row">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username'); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'ingame_password'); ?>
				<?php echo $form->textField($model,'ingame_password'); ?>
				<?php echo $form->error($model,'ingame_password'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'retype_ingame_password'); ?>
				<?php echo $form->textField($model,'retype_ingame_password'); ?>
				<?php echo $form->error($model,'retype_ingame_password'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email'); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>

			<div class="row">
				<?php $model->web_password = null; #don't send the password in plaintext! ?>
				<?php echo $form->labelEx($model,'web_password'); ?>
				<?php echo $form->passwordField($model,'web_password'); ?>
				<?php echo $form->error($model,'web_password'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($model,'retype_web_password'); ?>
				<?php echo $form->passwordField($model,'retype_web_password'); ?>
				<?php echo $form->error($model,'retype_web_password'); ?>
			</div>


			<div class="row buttons">
				<?php echo CHtml::submitButton('Submit'); ?>
			</div>

		<?php $this->endWidget(); ?>
	</div><!-- form -->
<?php endif; ?>
