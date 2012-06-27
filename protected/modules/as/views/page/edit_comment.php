<?php
	$this->pageTitle=Yii::app()->name . ' - Edit Comment';
	$this->breadcrumbs=array(
		'',
	);
?>

<h1>Edit Comment</h1>

<?php if(Yii::app()->user->hasFlash('edit-comment')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('edit-comment'); ?>
	<?=CHtml::link('Click here to go to the page', Yii::app()->user->getFlash('edit-comment:uri'))?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edit-comment-form',
	'enableClientValidation'=>true,
	'action' => $action,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row container showgrid">
		<div class="span-10">
			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model,'title',array('size'=>51)); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>

		<div class="span-9 last">
		</div>
	</div>

	<div class="row">
		<?=$form->labelEx($model,'content')?>
		<?php
	    $this->widget(
		    'ext.markitup.EMarkitupWidget',
		    array(
		        'model'=>$model,
		        'attribute'=>'content',
		        'htmlOptions'=>array('rows'=>15, 'cols'=>70),
		        'options'=>array(
		             'previewParserPath'=>
		                 Yii::app()->urlManager->createUrl('//as/page/editPreview')
		        )
		    )
		);
	?>
		<?=$form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'markup'); ?>
		<?php echo $form->dropDownList($model, 'markup', ContentMakeup::UserAllowed()); ?>
		<?php echo $form->error($model,'markup'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit',array('class'=>'button grey')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
		
