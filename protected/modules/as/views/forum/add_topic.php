<div id="formcenter">
	<h2><?=Yii::t('forum', 'Create A Topic')?></h2>

	<p><?=Yii::t('forum', 'Please fill all required fields and hit the submit button once your done.')?></p>

	<?php if($model->hasErrors()): ?>
	<div class="errordiv">
		<?php echo CHtml::errorSummary($model); ?>
	</div>
	<?php endif; ?>
	
	<?php echo CHtml::form('', 'post', array('class'=>'frmcontact')); ?>
	
	<div>
		
		<?php echo CHtml::activeLabel($model, 'name'); ?>
		<?php echo CHtml::activeTextField($model, 'name', array( 'class' => 'textboxcontact' )); ?>
		<?php echo CHtml::error($model, 'name', array( 'class' => 'errorfield' )); ?>

		<br />
		
		<?php echo CHtml::activeLabel($model, 'description'); ?>
		<?php echo CHtml::activeTextField($model, 'description', array( 'class' => 'textboxcontact' )); ?>
		<?php echo CHtml::error($model, 'description', array( 'class' => 'errorfield' )); ?>		
		
		<br />
		<?php echo CHtml::activeLabel($model, 'message'); ?><br />
		<?php echo CHtml::activeTextArea($model, 'message'); ?>
		<?php echo CHtml::error($model, 'message', array( 'class' => 'errorfield' )); ?>
		
		<br />
		
		<p>
			<?php echo CHtml::submitButton(Yii::t('adminglobal', 'Submit'), array('class'=>'submitcomment', 'name'=>'submit')); ?>
		</p>
		
	</div>
	
	<?php echo CHtml::endForm(); ?>
	
</div>
