<h2><?=CHtml::encode($email->email) ?></h2>
<?php $this->widget('bootstrap.widgets.BootDetailView', array(
	'data'=>$email,
	'attributes'=>array(
		array('name'=>'id', 'label'=>'email id'),
		array('name'=>'email', 'label'=>'email address'),
		array('name'=>'key', 'label'=>'activation key'),
		array('name'=>'status', 'label'=>'status'),
		array('name'=>'is_primary', 'label'=>'is primary email address'),
		array('name'=>'registered_date', 'label'=>'registered date'),
		array('name'=>'activated_date', 'label'=>'activated_date'),
		array('name'=>'ip', 'label'=>'ip of registerer'),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.BootModal', array('id'=>'response'.(int)$email->id)); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>Change email</h3>
	</div>
 
	<div class="modal-body">
		No content
	</div>
<?php $this->endWidget(); ?>

<?php $this->beginWidget('bootstrap.widgets.BootModal', array('id'=>'changeStatus'.(int)$email->id)); ?>

	<?php $form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
		'id' => 'email-update-form',
		'action' => array('updateEmail', 'id' => $email->id),
		'enableAjaxValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true
		)
	))?>
 
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>Change email</h3>
		</div>
 
		<div class="modal-body">
			<?=$form->textFieldRow($email, 'email', array('prepend'=>'@'));?>
			<?=$form->dropDownListRow($email, 'status', $email->statusOptions); ?>
			<?= $form->checkboxRow($email, 'is_primary'); ?>
		</div>
		 
		<div class="modal-footer">
			<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'reset', 'icon'=>'remove', 'label'=>'cancel', 'htmlOptions' => array('data-dismiss' => 'modal')));?>
			<?php $this->widget('bootstrap.widgets.BootButton', array(
				'buttonType'=>'ajaxSubmit',
				'url' => array('updateEmail', 'id' => $email->id),
				'ajaxOptions' => array(
					'success' => 'js:function(data) {
						$("#response'.(int)$email->id.' .modal-header h3").text("Successfully updated email!");
						$("#response'.(int)$email->id.' .modal-body").html(data);
						$("#response'.(int)$email->id.'").modal("toggle");
					}',
					'error' => 'js:function(xhr, _, _) {

						$("#response'.(int)$email->id.' .modal-header h3").text("Could not update email!");
						$("#response'.(int)$email->id.' .modal-body").html(xhr.responseText) ;
						$("#response'.(int)$email->id.'").modal("toggle");
					}',
				),
				'type'=>'primary',
				'icon'=>'ok white',
				'label'=>'Submit',
				'htmlOptions' => array('data-dismiss' => 'modal'),
			));?>
		</div>
	<?php $this->endWidget(); ?> 
<?php $this->endWidget(); ?>

<?php $this->beginWidget('bootstrap.widgets.BootModal', array('id'=>'delete'.(int)$email->id)); ?>

	<?php $form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
		'action' => array('deleteEmail', 'id' => $email->id),
	))?>
 		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3><span class="icon-exclamation-sign"></span> Are you shure?</h3>
		</div>
 
		<div class="modal-body">
			Are you shure you want to delete the email address &quot;<?=CHtml::encode($email->email)?>&quot;
		</div>
		 
		<div class="modal-footer">
			<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'reset', 'icon'=>'remove', 'label'=>'no', 'htmlOptions' => array('data-dismiss' => 'modal')));?>
			<?php $this->widget('bootstrap.widgets.BootButton', array(
				'buttonType'=>'ajaxSubmit',
				'url' => array('deleteEmail', 'id' => $email->id),
				'ajaxOptions' => array(
					'success' => 'js:function(data) {
						$("#response'.(int)$email->id.' .modal-header h3").text("Successfully deleted email!");
						$("#response'.(int)$email->id.' .modal-body").html(data);
						$("#response'.(int)$email->id.'").modal("toggle");
						$("[href=#email-'.(int)$email->id.']").parent().css("display", "none");
						setTimeout(function() {
							$("#response'.(int)$email->id.'").modal("toggle");
							$("#user-tabs a:first").tab("show");
						}, 2000);
					}',
					'error' => 'js:function(xhr, _, _) {

						$("#response'.(int)$email->id.' .modal-header h3").text("Could not delete email!");
						$("#response'.(int)$email->id.' .modal-body").html(xhr.responseText) ;
						$("#response'.(int)$email->id.'").modal("toggle");
					}',
				),
				'type'=>'primary',
				'icon'=>'trash white',
				'label'=>'delete',
				'htmlOptions' => array('data-dismiss' => 'modal'),
			));?>
	</div>
	<?php $this->endWidget();?> 
<?php $this->endWidget(); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.BootButton', array(
		'label'=>'Delete',
		'url'=>'#delete'.(int)$email->id,
		'type'=>'primary',
		'icon' => 'trash white',
		'htmlOptions'=>array('data-toggle'=>'modal'),
	)); ?>
	<?php $this->widget('bootstrap.widgets.BootButton', array(
		'label'=>'Change',
		'url'=>'#changeStatus'.(int)$email->id,
		'type'=>'primary',
		'icon' => 'pencil white',
		'htmlOptions'=>array('data-toggle'=>'modal'),
	)); ?>
</div>
