<h1>Create a blog</h1>
<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array( 
    'id'=>'blog-form', 
    'enableAjaxValidation'=>true, 
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name', array('class'=>'span5')); ?>
	<?php echo $form->textAreaRow($model,'descriptionSource', array('class'=>'span8')); ?>
	
	<?php if(isset($model->description)): ?>
		<h3>Preview:</h3>
		<fieldset class="well">
			<?=$model->description?>
		</fieldset>
	<?php endif; ?>

    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.BootButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Create blog', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>

