<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array( 
    'id'=>'entity-form', 
    'enableAjaxValidation'=>false, 
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model,'title', array('class'=>'span5')); ?>
	<?php echo $form->textAreaRow($model,'source', array('class'=>'span8')); ?>
	
	<?php if(isset($model->content)): ?>
		<h3>Preview:</h3>
		<fieldset>
			<?=$model->content?>
		</fieldset>
	<?php endif; ?>

    <?php echo $form->dropDownListRow($model,'access',Entity::getAccessOptions()); ?>
    <?php echo $form->dropDownListRow($model,'status',Entity::getStatusOptions()); ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.BootButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>$model->isNewRecord ? 'Create' : 'Save', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>

