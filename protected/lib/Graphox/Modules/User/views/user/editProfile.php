<h1>Update your profile.</h1>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'entity-form', 
    'enableAjaxValidation'=>false, 
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'firstName', array('class'=>'span5')); ?>
	<?php echo $form->textFieldRow($model,'lastName', array('class'=>'span5')); ?>
	
	<?php echo $form->checkBoxRow($model,'publicEmail', array('default' => 0)); ?>
	<?php echo $form->checkBoxRow($model,'publicName', array('default' => 0)); ?>
	
	<?php echo $form->textFieldRow($model,'country', array('class'=>'span5')); ?>
	<?php echo $form->textFieldRow($model,'city', array('class'=>'span5')); ?>
	
	<?php echo $form->textFieldRow($model,'homepage', array('class'=>'span5')); ?>
	
	<?php echo $form->checkBoxRow($model,'canComment', array('default' => 0)); ?>
	
	<?php echo $form->markdownEditorRow($model,'source'); ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Update Profile'
        )); ?>
    </div> 

<?php $this->endWidget(); ?>

