<h3>Profile information</h3>

<?php if(Yii::app()->user->hasFlash('edit-profile')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('edit-profile'); ?>
</div>

<?php endif; ?>

<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'profile-edit-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
)); ?>

	<div class="form">
	
		<?=$form->errorSummary($profile_model)?>
	
		<h4>Homepage</h4>
		<fieldset>
			<div class="row">
				<?=$form->labelEx($profile_model,'homepage'); ?>
				<?=$form->textField($profile_model,'homepage')?>
				<?=$form->error($profile_model,'homepage')?>					
			</div>
		</fieldset>
	
		<h4>Profile page</h4>
		<fieldset>
			<div class="row" >
				<p>
					Image selector not implemented yet
					<?php /*TODO: image selector */ ?>		
				</p>
			</div>
		</fieldset>
	

		<h4>Profile page</h4>
		<fieldset>
			<div class="container showgrid">
				<div class="span-8">
					<?php echo $form->labelEx($profile_model,'page_title'); ?>
					<?php echo $form->textField($profile_model,'page_title',array('size'=>40)); ?>
					<?php echo $form->error($profile_model,'page_title'); ?>
				</div>

				<div class="span-8 last">
					<?php echo $form->labelEx($profile_model,'page_description'); ?>
					<?php echo $form->textField($profile_model,'page_description',array('size'=>51)); ?>
					<?php echo $form->error($profile_model,'page_description'); ?>
				</div>
			</div>

			<div class="row">
				<?=$form->labelEx($profile_model,'page_content')?>
				<?php
				$this->widget(
					'ext.markitup.EMarkitupWidget',
					array(
						'model'=>$profile_model,
						'attribute'=>'page_content',
						'htmlOptions'=>array('rows'=>15, 'cols'=>70),
						'options'=>array(
						     'previewParserPath'=>
						         Yii::app()->urlManager->createUrl('//as/page/editPreview')
						)
					)
				);
			?>
				<?=$form->error($profile_model,'page_content'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($profile_model,'page_markup'); ?>
				<?php echo $form->dropDownList($profile_model, 'page_markup', ContentMakeup::userAllowed()); ?>
				<?php echo $form->error($profile_model,'page_markup'); ?>
			</div>

			<div class="row">
				<?php echo $form->labelEx($profile_model,'allow_comments'); ?>
				<?php echo $form->checkBox($profile_model, 'allow_comments'); ?>
				<?php echo $form->error($profile_model,'allow_comments'); ?>
			</div>

		</fieldset>
	
		<div class="row">
			<?=CHtml::submitButton('save')?>
		</div>
	</div>
<?php $this->endWidget(); ?>

