<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'entity-form', 
    'enableAjaxValidation'=>false, 
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model,'title', array('class'=>'span5')); ?>
	<?php echo $form->markdownEditorRow($model,'source'); ?>
	

    <?php echo $form->dropDownListRow($model,'access',Entity::getAccessOptions()); ?>
    <?php echo $form->dropDownListRow($model,'status',Entity::getStatusOptions()); ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>$model->isNewRecord ? 'Create' : 'Save', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>

