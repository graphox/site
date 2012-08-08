<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
	'type'=>'inline',
	'action' => array('changeStatus', 'id' => $model->id),
)); ?>
	<?=$form->errorSummary($model)?>

	<div class="row">	
		<h3>Change status</h3>
		<p>
			Current status: <?=$model->status?>
		</p>
		
		<?php
		$items = array(
			array('label' => 'invalid status please contact a dev!')
		); ?>
		
		<?php if($model->status == 'active'): ?>
			<?php $items = array(
				   'banned' => 'ban user'
			); ?>
		<?php elseif($model->status == 'banned'): ?>		
			<?php $items = array(
				'active' => 'activate',
				'email' => 'verifying email',
			) ?>
		<?php elseif($model->status == 'admin'): ?>
			<?php $items = array(
					'banned' => 'ban user',
					'active' => 'activate account'
				) ?>
		<?php elseif($model->status == 'email'): ?>
			<?php $items = array(
					'active' => 'activate, NOTE: this will cancel the user email validation.'
				) ?>
		<?php elseif($model->status == 'both'): ?>
			<?php $items = array(
					'banned' => 'ban user',
					'email' => 'admin activate'
				) ?>
		<?php endif; ?>
		
		<?php echo $form->radioButtonListRow($model, 'status', $items); ?>


		<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'icon'=>'ok', 'label'=>'change')); ?>
		
		<p>
			<strong>Note:</strong>
			Changing the status may send emails to users and to other admins.
		</p>

	</div>

<?php $this->endWidget(); ?>
