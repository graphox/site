<?php if(Yii::app()->user->hasFlash('edit-forum')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('edit-forum'); ?>
	<?=CHtml::link('Click here to go to the forum', array('//as/forum/viewforum', 'id' => Yii::app()->user->getFlash('edit-forum:id')))?>
</div>

<?php else: ?>

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'=>'forumform',
	)); ?>
		<div class="form">

			<p class="note">Fields with <span class="required">*</span> are required.</p>

			<?= $form->errorSummary($model); ?>
					
			<div class="row">
				<?php echo $form->labelEx($model,'name'); ?>
				<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50, "tabindex" => 1)); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>

			<div class="row">
				<?=$form->labelEx($model, 'description')?>
				<?=$form->textArea($model, 'description')?>
				<?=$form->error($model, 'description')?>
			</div>
		
			<div class="row">
				<?php echo $form->labelEx($model,'no_parent'); ?>
				<?php echo $form->checkBox($model, 'no_parent', array('value'=>1, 'uncheckValue'=>0)); ?>
				<?php echo $form->error($model,'no_parent'); ?>
			
				<?=$form->labelEx($model, 'parent_id')?>
				<?php $this->widget('application.components.Relation', array(
					'model' => $model->parent ? $model->parent : 'Forum',
					'field' => 'parent_id',
					'fields' => 'name',
				   	'parentObjects' => Forum::model()->findAll(),
				   	'relation' => 'parent',
				   	'showAddButton' => false
				));	?>	
				<?=$form->error($model, 'parent_id')?>
		
			
			</div>
			
			<div class="row">
				<?=CHtml::submitButton('Post', array('id' => 'submit')); ?>
			</div>
		</div>
	<?php $this->endWidget(); ?>
	
<?php endif; ?>
