<?php
	$this->pageTitle = Yii::app()->name . ' - Login';
	$this->title = 'Login or Register';
	$this->layout = '//layouts/column2';
?>

<div class="container">
	<div class="span-10">
		<h2 class="line-divider">Normal login</h2>

		<p>
			<strong>Please fill out the following form with your login credentials:</strong>
		</p>

		<p>
			Fields with <span class="required">*</span> are required.
		</p>

		<p>
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
				'htmlOptions' => array('class' => 'admin-form'),
				'action' => isset($_GET['returnurl']) ? array('//as/auth', 'returnurl' => $_GET['returnurl']) : array('//as/auth'),
			
			)); ?>

				<fieldset>
					<div>
						<?php echo $form->labelEx($model,'email'); ?>
						<?php echo $form->textField($model, 'email', array('class' => 'form-poshytip', 'title' => 'Enter your email adress')); ?>
						<?php echo $form->error($model,'email'); ?>
					</div>

					<div>
						<?php echo $form->labelEx($model,'password'); ?>
						<?php echo $form->passwordField($model,'password', array('class' => 'form-poshytip', 'title' => 'Enter your password')); ?>
						<?php echo $form->error($model,'password'); ?>
					</div>

					<div class="row buttons">
						<?php echo CHtml::submitButton('Login', array('id' => 'submit')); ?>
					</div>
				</fieldset>
			<?php $this->endWidget(); ?>
		</p>
	</div>

	<div class="span-9 last">
		<h2 class="line-divider">Oauth / Openid login</h2>

		<p>
			<strong>Do you already have an account on one of these sites? Click the logo to log in with it here:</strong>
		</p>

		<p>
			<?php $this->widget('as.components.widgets.AsEAuthWidget', array('action' => '//as/auth'));	?>
		</p>
		
		<h2 class="line-divider">Register</h2>
		
		<p>
			<?=CHtml::link('Click here to go to the registration page.', array('//as/auth/register')) ?>
		</p>
	</div>
</div>
