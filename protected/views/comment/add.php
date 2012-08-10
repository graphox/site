<?php
/** @var CommentEntity $model */
/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
    'id'=>'inlineForm',
    'type'=>'inline',
    'htmlOptions'=>array('class'=>'well'),
	'action' => array('/comment/add'),
)); ?>

<?=$form->errorSummary($model)?>
 
<?=$form->textAreaRow($model, 'source', array('class'=>'span7', 'rows'=>5))?>
<?=CHtml::hiddenfield(get_class($model).'[parent_id]', $model->parent_id)?>
<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'icon'=>'arrow-right', 'label'=>'Create comment.')); ?>
 
<?php $this->endWidget(); ?>