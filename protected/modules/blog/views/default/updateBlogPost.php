<h1>Update blogpost</h1>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array( 
    'id'=>'entity-form', 
    'enableAjaxValidation'=>false, 
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p> 

    <?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'title', array('class'=>'span5')); ?>
	<?php echo $form->markdownEditorRow($model,'source'); ?>

		
	<?= $form->toggleButtonRow($model, 'canComment'); ?>

	<?php // echo $form->textFieldRow($model, 'tagString');?>
	<?php echo $form->select2Row($model, 'tagString', array(
		'asDropDownList' => false,
		'options' => array(
			'tags' => $model->tags,
			'tokenSeparators' => array(',', ' '),
			'placeholder' => 'Tags',
			'width' => '100%',
		),
		'class' => 'span5'
	));	?>
	

	
	
    <div class="form-actions"> 
        <?php $this->widget('bootstrap.widgets.TbButton', array( 
            'buttonType'=>'submit', 
            'type'=>'primary', 
            'label'=>'save', 
        )); ?>
    </div> 

<?php $this->endWidget(); ?>


