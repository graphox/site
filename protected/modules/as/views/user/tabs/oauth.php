<h3>Activate your account</h3>

<p>
	Please update your account by clicking the button below,
	<strong>note that you will have to activate your email before you can use your account again.</strong>
</p>

<?php $form = $this->beginWidget('CActiveForm', array(
	'id' => 'account-oauth-activate-form',
	'enableAjaxValidation' => true,
	'enableClientValidation' => true,
	'action' => array('//as/user/oauth-activate'),
	'clientOptions' => array(
		'validationUrl' => array('//as/user/oauth-activate'),
	),
)); ?>
	<div class="row">
		<?=$form->labelEx($model, 'email'); ?>
		<?=$form->textField($model, 'email')?>
		<?=$form->error($model, 'email')?>					
	</div>

	<div class="row">
		<?=$form->labelEx($model, 'web_password'); ?>
		<?=$form->textField($model, 'web_password')?>
		<?=$form->error($model, 'web_password')?>					
	</div>
				
	<div class="row">
		<?=$form->labelEx($model, 'ingame_password'); ?>
		<?=$form->textField($model, 'ingame_password')?>
		<?=$form->error($model, 'ingame_password')?>
	</div>
	
	<div class="row">
		<?=CHtml::submitButton('activate')?>
	</div>
<?php $this->endWidget() ?>		
