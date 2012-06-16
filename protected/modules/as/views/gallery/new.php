<?php $this->beginClip('first'); ?>
	</h1>Create a new galery</h1>
	<?php $form = $this->beginWidget('CActiveForm', array(
		'id'=>'gallery-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		'htmlOptions' => array('class' => 'admin-form'),
		'action' => isset($action) ? $action : array('//as/gallery/new'),
			
		)); ?>

	<fieldset>
		<div>
			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model, 'title', array('class' => 'form-poshytip', 'title' => 'The title / name of your gallery.')); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</fieldset>

	<fieldset>
		<div>
			<?php echo $form->labelEx($model,'description'); ?>
			<?php echo $form->textField($model, 'description', array('class' => 'form-poshytip', 'title' => 'Brief description.')); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
	</fieldset>
	
	<fieldset>
		<div>
			<?php echo $form->labelEx($model,'content'); ?>
			<?php echo $form->textArea($model, 'content', array('class' => 'form-poshytip', 'title' => 'Long text.')); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</fieldset>
	
	<fieldset>
		<div>
			<?php echo $form->labelEx($model,'allow_comments'); ?>
			<?php echo $form->checkBox($model, 'allow_comments', array('class' => 'form-poshytip', 'title' => 'The title / name of your gallery.')); ?>
			<?php echo $form->error($model,'allow_comments'); ?>
		</div>
	</fieldset>
	
	<h4>images</h4>
	
	<div class="uploaded">
	<?php foreach($model->images as $image): ?>
	<fieldset>
		<div>
			<input class="image" type="text" value="<?=$image?>" name="GalleryForm[images][]"/>
			<img src="<?=$this->createUrl('//as/gallery/rawimage', array('id' => $image))?> " />
		</div>
	</fieldset>
	<?php endforeach; ?>
	</div>
	
	<?=CHtml::submitButton('Save', array('id' => 'submit'))?>

<?php $this->endWidget(); ?>

<?php $this->endClip();?>
<?php $this->beginClip('second'); ?>

<div style="width:100%;height:100%;border-color:white;border-style:dotted;border-width:5px;">
	<p>
		drag and drop pictures to here <strong style="display:block;margin:5px;">or</strong> select them from your pc
	</p>
	<div style="margin:auto;width:100%">
		<?php $this->widget('ext.EAjaxUpload.EAjaxUpload', array(
			'id'=>'uploadFile',
			'config'=>array(
				'action'=> Yii::app()->createUrl('//as/gallery/upload'),
				'allowedExtensions' => array("jpg","jpeg","gif","png"),
				'sizeLimit'=>10 * 1024 * 1024,// maximum file size in bytes
				'onComplete' =>
<<<JS
js:
			function(id, fileName, responseJSON)
			{
				if(responseJSON.error)
				{
					$('ul.qq-upload-list li:nth-child('+(id+1)+') span.qq-upload-failed-text').text('Failed: '+responseJSON.error);
				}
				
				if(responseJSON.success)
				{
					//TODO: style a bit
					var element = $('<input class="image" type="text" value="" name="GalleryForm[images][]"/>');
					element.val(responseJSON.fileId);
					$('#gallery-form div.uploaded').append(element);
					
					var preview = $('<image />');
					preview.attr('src', '{$this->createUrl('//as/gallery/rawimage')}?id='+responseJSON.fileId);
					
					$('.preview').append(preview);
				}
			}
JS
,
			'showMessage'=>"js:function(message){ $('<div>'+message+'</div>').dialog({modal:true}); }"
			)
		)); ?>
	</div>
</div>

<div class="preview">
</div>
<?php $this->endClip();?>
