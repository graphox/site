<h1>Account activation</h1>
	
<?php if(Yii::app()->user->hasFlash('activate.success')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('activate.success'); ?>
		<?=CHtml::link('Click here to login.', array('//as/auth'))?>
	</div>
<?php else: ?>
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'activate-form',
		)); ?>
			<p>
				Please enter the activation key you received by mail
			</p>

			<?=$form->errorSummary($model); ?>

			<div class="row">
				<?php echo $form->labelEx($model,'hash'); ?>
				<?php echo $form->textField($model,'hash'); ?>
				<?php echo $form->error($model,'hash'); ?>
			</div>

			<div class="row buttons">
				<?php echo CHtml::submitButton('Submit'); ?>
			</div>

		<?php $this->endWidget(); ?>
	</div><!-- form -->
<?php endif; ?>
