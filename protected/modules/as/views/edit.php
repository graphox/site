			<div class="form">
				<h2><?=isset($header)?$header:'Leave a Reply'?></h2>
						
				<?php $form = $this->beginWidget('CActiveForm', array(
					'id'=>'commentform',
					'action' => isset($action)?$action:array(),
				)); ?>

				<p class="note">Fields with <span class="required">*</span> are required.</p>

				<?= $form->errorSummary($model); ?>
					
				<div class="row">
					<?php echo $form->labelEx($model,'title'); ?>
					<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50, "tabindex" => 1)); ?>
					<?php echo $form->error($model,'title'); ?>
				</div>

				<div class="row">
					<?=$form->labelEx($model,'content')?>
					<?php $this->widget(
						'ext.markitup.EMarkitupWidget',
						array(
							'model'=>$model,
							'attribute'=>'content',
							'htmlOptions'=>array('rows'=>15, 'cols'=>70),
							'options'=>array(
								 'previewParserPath' => Yii::app()->urlManager->createUrl('//as/page/editPreview')
							)
						)
					); ?>
							
					<?=$form->error($model,'content'); ?>
				</div>
					
				<div class="row">
					<?php echo $form->labelEx($model,'markup'); ?>
					<?php echo $form->dropDownList($model, 'markup', ContentMakeup::userAllowed()); ?>
					<?php echo $form->error($model,'markup'); ?>
				</div>						

				<div class="row">
					<?php echo CHtml::submitButton('Post', array('id' => 'submit')); ?>
				</div>

				<?php $this->endWidget(); ?>
			</div>

