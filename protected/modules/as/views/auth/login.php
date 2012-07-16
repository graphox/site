<?php
	$this->pageTitle = Yii::app()->name . ' - Login';
	$this->title = 'Login or Register';
	$this->layout = '//layouts/column2';
?>

<div class="container">
	<div class="span-10">
		<h2>Normal login</h2>

		<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
				'action' => array('//as/auth'),
			
			)); ?>


				<p>
					<strong>Please fill out the following form with your login credentials:</strong>
				</p>

				<p>
					Fields with <span class="required">*</span> are required.
				</p>
				
				<?=$form->errorSummary($model)?>

				<div class="row">
					<?php echo $form->labelEx($model,'username'); ?>
					<?php echo $form->textField($model, 'username'); ?>
					<?php echo $form->error($model,'username'); ?>
				</div>

				<div class="row">
					<?php echo $form->labelEx($model,'password'); ?>
					<?php echo $form->passwordField($model,'password'); ?>
					<?php echo $form->error($model,'password'); ?>
				</div>

				<div class="row buttons">
					forgot password | register <?php echo CHtml::submitButton('Login', array('id' => 'submit')); ?>
				</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div class="span-9 last">
		<h2>Oauth / Openid login</h2>

		<p>
			<strong>Do you already have an account on one of these sites? Click the logo to log in with it here:</strong>
		</p>

		<p>
			<?php $this->widget('as.components.widgets.AsEAuthWidget', array('action' => '//as/auth'));	?>
		</p>
		
		<h2>Register</h2>
		
		<p>
			<?=CHtml::link('Click here to go to the registration page.', array('//as/auth/register')) ?>
		</p>
	</div>
</div>
