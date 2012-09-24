<h1>Update your profile.</h1>
<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array( 
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
	
	<?php echo $form->textAreaRow($model,'source', array('class'=>'span8')); ?>
	
	<?php if(isset($model->content)): ?>
		<h3>Preview:</h3>
		<fieldset>
			<?=$model->content?>
		</fieldset>
	<?php endif; ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.BootButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Update Profile'
        )); ?>
    </div> 

<?php $this->endWidget(); ?>

