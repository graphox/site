<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'tags-pages-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => isset($action) ? $action : array('admin/tagsPages/add'),
	'clientOptions' => array( 'validationUrl' => array('admin/tagsPages/add'))
	
));
?>
<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php
	echo $form->errorSummary($form_model);
?>






<label for="Pages">Belonging Pages</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'page',
							'fields' => 'module',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?><label for="Tags">Belonging Tags</label>
<?php
					$this->widget('application.components.Relation', array(
							'model' => $form_model,
							'relation' => 'tag',
							'fields' => 'tag',
							'allowEmpty' => false,
							'style' => 'dropdownlist',
							)
						); ?>
<footer>
	<div class="submit_link">
		<?php echo CHtml::submitButton('Submit'); ?>	</div>
</footer>

<?php $this->endWidget(); ?>
