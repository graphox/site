<h1>Create a blog</h1>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'blog-form', 
    'enableAjaxValidation'=>true, 
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name', array('class'=>'span5')); ?>
	<?php echo $form->markdownEditorRow($model,'descriptionSource'); ?>
	
    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'Create blog', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>

