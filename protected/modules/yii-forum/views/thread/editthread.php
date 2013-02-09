<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>array_merge(
            $model->getBreadcrumbs(true),
            array('Edit')
        ),
    ));
?>

<div class="form" style="margin:20px;">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'thread-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
	),
    )); ?>

        <div class="row">
            <?php echo $form->labelEx($model,'subject'); ?>
            <?php echo $form->textField($model,'subject'); ?>
            <?php echo $form->error($model,'subject'); ?>
        </div>

        <div class="row rememberMe">
            <?php echo $form->checkBox($model,'isSticky',array('uncheckValue'=>0)); ?>
            <?php echo $form->labelEx($model,'isSticky'); ?>
            <?php // echo $form->error($model,'lockthread'); ?>
        </div>

        <div class="row rememberMe">
            <?php echo $form->checkBox($model,'isLocked',array('uncheckValue'=>0)); ?>
            <?php echo $form->labelEx($model,'isLocked'); ?>
            <?php // echo $form->error($model,'lockthread'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Submit'); ?>
        </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
