<h3>Your ingame names</h3>
<p>
	The names to protect on our gameserver, and wich can be used to login onto your account.
</p>

<?php if(Yii::app()->user->hasFlash('edit-names')): ?>
	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('edit-names'); ?>
	</div>
<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('delete-names-error')): ?>
	<div class="flash-error">
		<?php echo Yii::app()->user->getFlash('delete-names-error'); ?>
	</div>
<?php endif; ?>

<?php if($model->names && count($model->names) > 0): ?>
<h4>Your names</h4>
	<ul>
		<?php foreach($model->names as $name): ?>
		<li>
			<strong><?=$name->name?></strong>
			<?php if($name->status == Names::STATUS_CANCELLED): ?>
				<strong>Cancelled</strong>
			<?php elseif($name->status == Names::STATUS_PENDING): ?>
				<strong>pending</strong>
			<?php elseif($name->status == Names::STATUS_ACTIVE): ?>
				active
			<?php else: ?>
				Status: <?=$name->status?>
			<?php endif; ?>
						
			<div style="float:right">
				<?= CHtml::beginForm(array('//as/user/edit')); ?>
					<?= CHtml::hiddenField('delete-name', $name->id); ?>
					<noscript>
						<?=CHtml::submitButton('delete'); ?>
					</noscript>
					
					<div style="display:none" class="js-enabled name-delete-buttons">
						<span class="icon icon-delete"></span>
					</div>
				<?= CHtml::endForm() ?>
			</div>
			<div style="clear:both"> 
		</li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
	<div class="flash-notice">
		You haven't set any names yet.
	</div>
<?php endif; ?>

<h4>Add name</h4>
<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'account-edit-form',
		'enableAjaxValidation' => true,
		'enableClientValidation' => true,
	)); ?>

<div class="form">
	<?=$form->errorSummary($name_model); ?>
	
	<div class="row">
		<?=$form->labelEx($name_model, 'name'); ?>
		<?=$form->textField($name_model, 'name', array('placeholder' => 'Add a name'))?>
		<?=$form->error($name_model, 'name')?>

		<?= CHtml::submitButton('save'); ?>
	</div>
</div>

<?php $this->endWidget(); ?>
