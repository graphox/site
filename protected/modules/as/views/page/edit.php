<?php
	$this->pageTitle=Yii::app()->name . ' - Edit page';
	$this->breadcrumbs=array(
		'',
	);
?>

<h1>Edit page</h1>

<?php if(Yii::app()->user->hasFlash('edit-page')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('edit-page'); ?>
	<?=CHtml::link('Click here to go to the page', array('//as/page', 'id' => Yii::app()->user->getFlash('edit-page:id')))?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edit-page-form',
	'enableClientValidation'=>true,
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
			<?php echo $form->labelEx($model,'description'); ?>
			<?php echo $form->textField($model,'description',array('size'=>51)); ?>
			<?php echo $form->error($model,'description'); ?>
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

	<?php /** TODO: tags widget
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
		*/ ?>

	<div class="row">
		<?php #TODO: widget ?>
		<?php echo $form->labelEx($model,'uri'); ?>
		<?php echo $form->textField($model,'uri',array('size'=>51)); ?>
		<strong>This is not in use at the moment</strong>
		<?php echo $form->error($model,'uri'); ?>
	</div>

	<div class="row">
	<?php #TODO: browse lightbox ?>
	<?php echo $form->labelEx($model,'parent_id'); ?>		
	<?php $this->widget('application.components.Relation', array(
		'model' => 'Pages',
		'field' => 'parent_id',
		'fields' => 'title',
	   	'parentObjects' => Pages::model()->findAllByAttributes(array('module' => 'web')),
	   	'relation' => 'parent',
	   	'showAddButton' => false,
	   	'htmlOptions' => array('style' => 'width: 100px;')
	));
	?>	
	<?php echo $form->error($model,'parent_id'); ?>
	
	<?php echo $form->labelEx($model,'no_parent'); ?>
	<?php echo $form->checkBox($model, 'no_parent', array('value'=>1, 'uncheckValue'=>0)); ?>
	<?php echo $form->error($model,'no_parent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'markup'); ?>
		<?php echo $form->dropDownList($model, 'markup', ContentMakeup::userAllowed()); ?>
		<?php echo $form->error($model,'markup'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'allow_comments'); ?>
		<?php echo $form->checkBox($model, 'allow_comments'); ?>
		<?php echo $form->error($model,'allow_comments'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit',array('class'=>'button grey')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>
		
