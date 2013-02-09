<?php
$this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'links'=>array_merge(
        $model->getBreadcrumbs(!$model->isNewRecord),
        array($model->isNewRecord?'New forum':'Edit')
    )
));
?>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'entity-form', 
    'enableAjaxValidation'=>false, 
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php
		$n = Forum::model()->findAll()
	?>
	<?php echo $form->select2Row($model, 'parentId', array(
		'data'=> count($n) > 0 ? CHtml::listData($n, 'id', 'title') : array('empty'=>'None (root)'))); ?>

	<?php echo $form->textFieldRow($model,'title', array('class'=>'span5')); ?>

	<?php echo $form->markdownEditorRow($model, 'descriptionSource'); ?>

	<?php echo $form->textFieldRow($model,'listorder', array('class'=>'span5')); ?>

	<?= $form->toggleButtonRow($model, 'isLocked'); ?>

    <div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>$model->isNewRecord ? 'Create' : 'Save', 
        )); ?>
    </div>
<?php $this->endWidget(); ?>

